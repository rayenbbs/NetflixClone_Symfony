<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MoviesController extends AbstractController
{

    public function __construct(private EntityRepository $entityRepository,
                                private CategoryRepository $categoryRepository){

    }

    #[Route('/movies', name: 'app_movies')]
    public function index(): Response
    {
        $entities=$this->entityRepository->getMovieEntities(null,100);
        shuffle($entities);
        $entity = $entities[0];
        $categories = $this->categoryRepository->findAll();
        return $this->render('movies/index.html.twig', [
            'controller_name' => 'MoviesController',
            'categories'=>$categories,
            'entity'=> $entity,
        ]);
    }
}
