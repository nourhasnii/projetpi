<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArtiumController extends AbstractController
{

    #[Route('/back', name: 'app_home')]
    public function back(): Response
    {
        return $this->render('back/base.html.twig', [
            'home' => 'home',
        ]);
    }

    #[Route('/artium', name: 'app_artium')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('artium/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
