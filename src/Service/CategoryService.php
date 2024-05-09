<?php

namespace App\Services;


use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\CategoryRepository;
use App\Repository\EntityRepository;

class CategoryService
{
    private $categoryRepository;
    private $entityRepository;


    public function __construct(CategoryRepository $categoryRepository, EntityRepository $entityRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityRepository = $entityRepository;
    }

    public function showAllCategories()
    {
        $categories = $this->categoryRepository->findAll();
        return $categories;
    }
}