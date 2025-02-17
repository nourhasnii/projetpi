<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Course;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les catégories principales
        $categories = $entityManager->getRepository(Category::class)->findBy(['parent' => null]);

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/{categoryName}', name: 'category_courses')]
    public function showCategory(EntityManagerInterface $entityManager, string $categoryName): Response
    {

        $category = $entityManager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);

        

        $courses = $entityManager->getRepository(Course::class)->findBy(['categories' => $category]);
        $hasCourses = count($courses) > 0;

        return $this->render('category/show.html.twig', [
            'category' => $category, 
            'courses' => $courses, 
            'hasCourses' => $hasCourses,
        ]);
    }
    // Ajoutez cette méthode dans CategoryController

#[Route('/category/{categoryName}/cours/{courseId}', name: 'course_details')]
public function showCourseDetails(EntityManagerInterface $entityManager, string $categoryName, int $courseId): Response
{
    // Récupérer la catégorie par son nom
    $category = $entityManager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);

    // Récupérer le cours par son ID
    $course = $entityManager->getRepository(Course::class)->find($courseId);

    if (!$category || !$course) {
        throw $this->createNotFoundException('Catégorie ou cours non trouvé');
    }

    return $this->render('courses/course_details.html.twig', [
        'category' => $category,
        'course' => $course,
    ]);
}

}
