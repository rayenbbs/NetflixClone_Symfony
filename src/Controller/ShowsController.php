<?php

namespace App\Controller;

use App\Entity\Entity;
use App\Repository\CategoryRepository;
use App\Repository\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShowsController extends AbstractController
{

    public function __construct(private EntityRepository $entityRepository,
    private CategoryRepository $categoryRepository){

    }


    #[Route('/shows', name: 'app_shows')]
    public function index(): Response
    {
        $entities=$this->entityRepository->getTVShowEntities(null,100);
        shuffle($entities);
        $entity = $entities[0];
        $categories = $this->categoryRepository->findAll();

        return $this->render('shows/index.html.twig', [
            'controller_name' => 'ShowsController',
            'categories'=>$categories,
            'entity'=> $entity,
        ]);
    }
}
