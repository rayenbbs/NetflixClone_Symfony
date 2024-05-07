<?php

namespace App\Controller;

use App\Services\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    #[Route('/categories', name: 'categories')]
    public function showAllCategories(): Response
    {
        $categories = $this->categoryService->showAllCategories();

        return $this->render('categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}
