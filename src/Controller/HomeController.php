<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private EntityRepository  $entityRepository){}
    private function getRandomEntity() {
        $entities=$this->entityRepository->getEntities(null,7);
        shuffle($entities);
        return ($entities[0]);
    }


    #[Route('/home', name: 'app_home')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $entity = $this->getRandomEntity();
        $categories = $categoryRepository->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'categories' => $categories,
            'entity'=>$entity
            ]);
    }
}
