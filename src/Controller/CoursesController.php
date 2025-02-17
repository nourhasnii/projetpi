<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\CourseFile;
use App\Form\CourseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{categoryName}/cours', name: 'category_courses')]
class CoursesController extends AbstractController
{
    #[Route('', name: '_index', methods: ['GET', 'POST'])]
    public function index(string $categoryName, Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);
        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        $course = new Course();
        $course->setCategories($category);

        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('content_file')->getData();

            if (!empty($files)) {
                foreach ($files as $file) {
                    if ($file) {
                        $newFilename = uniqid() . '.' . $file->guessExtension();
                        $uploadDir = $this->getParameter('uploads_directory');

                        try {
                            $file->move($uploadDir, $newFilename);

                            // Création d'un nouvel objet CourseFile et association au cours
                            $courseFile = new CourseFile();
                            $courseFile->setFileName($newFilename);
                            $courseFile->setCourse($course);
                            $entityManager->persist($courseFile);
                        } catch (FileException $e) {
                            $this->addFlash('error', 'Une erreur s\'est produite lors de l\'upload.');
                        }
                    }
                }
            }

            $entityManager->persist($course);
            $entityManager->flush();

            $this->addFlash('success', 'Le cours a été ajouté avec succès.');
            return $this->redirectToRoute('category_courses_index', ['categoryName' => $categoryName]);
        }

        return $this->render('courses/index.html.twig', [
            'category' => $category,
            'courses'  => $category->getCourses(),
            'form'     => $form->createView(),
        ]);
    }

    #[Route('/{courseId}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(string $categoryName, int $courseId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = $entityManager->getRepository(Course::class)->find($courseId);
        if (!$course) {
            throw $this->createNotFoundException("Cours introuvable");
        }

        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('content_file')->getData();

            if (!empty($files)) {
                foreach ($files as $file) {
                    if ($file) {
                        $newFilename = uniqid() . '.' . $file->guessExtension();
                        $uploadDir = $this->getParameter('uploads_directory');

                        try {
                            $file->move($uploadDir, $newFilename);

                            $courseFile = new CourseFile();
                            $courseFile->setFileName($newFilename);
                            $courseFile->setCourse($course);
                            $entityManager->persist($courseFile);
                        } catch (FileException $e) {
                            $this->addFlash('error', 'Une erreur s\'est produite lors de l\'upload.');
                        }
                    }
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Le cours a été modifié avec succès.');

            return $this->redirectToRoute('category_courses_index', ['categoryName' => $categoryName]);
        }

        return $this->render('courses/index.html.twig', [
            'form'     => $form->createView(),
            'course'   => $course,
            'category' => $course->getCategories(),
            'courses'  => $course->getCategories()->getCourses(),
        ]);
    }

    #[Route('/{courseId}/delete', name: '_delete', methods: ['POST'])]
    public function delete(string $categoryName, int $courseId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = $entityManager->getRepository(Course::class)->find($courseId);
        if (!$course) {
            throw $this->createNotFoundException("Cours introuvable");
        }

        if ($this->isCsrfTokenValid('delete' . $course->getId(), $request->request->get('_token'))) {
            // Suppression des fichiers associés
            foreach ($course->getCourseFiles() as $file) {
                $filePath = $this->getParameter('uploads_directory') . '/' . $file->getFileName();
                if (file_exists($filePath)) {
                    unlink($filePath); // Supprimer le fichier du système de fichiers
                }
                $entityManager->remove($file); // Supprimer l'entrée de la base de données
            }

            $entityManager->remove($course); // Supprimer le cours
            $entityManager->flush();
            $this->addFlash('success', 'Le cours a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('category_courses_index', ['categoryName' => $categoryName]);
    }

    #[Route('/courses/{courseId}/file/{fileId}/delete', name: '_file_delete', methods: ['POST'])]
public function deleteCourseFile(string $categoryName, int $courseId, int $fileId, Request $request, EntityManagerInterface $entityManager): Response
{
    $course = $entityManager->getRepository(Course::class)->find($courseId);
    if (!$course) {
        throw $this->createNotFoundException("Cours introuvable");
    }

    $file = $entityManager->getRepository(CourseFile::class)->find($fileId);
    if (!$file) {
        throw $this->createNotFoundException("Fichier introuvable");
    }

    // Vérifier le token CSRF pour la sécurité
    if ($this->isCsrfTokenValid('delete_file' . $fileId, $request->request->get('_token'))) {
        // Supprimer le fichier du répertoire
        $filePath = $this->getParameter('uploads_directory') . '/' . $file->getFileName();
        if (file_exists($filePath)) {
            unlink($filePath); // Supprimer le fichier du système de fichiers
        }

        // Supprimer l'entrée du fichier dans la base de données
        $entityManager->remove($file);
        $entityManager->flush();
        $this->addFlash('success', 'Le fichier a été supprimé avec succès.');
    } else {
        $this->addFlash('error', 'Token CSRF invalide.');
    }

    // Rediriger vers la page de gestion des cours après suppression
    return $this->redirectToRoute('category_courses_edit', ['categoryName' => $categoryName, 'courseId' => $courseId]);
}


    
}
