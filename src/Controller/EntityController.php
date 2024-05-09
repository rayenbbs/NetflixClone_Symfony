<?php

namespace App\Controller;

use App\Repository\EntityRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;


class EntityController extends AbstractController
{
    private EntityRepository $entityRepository;
    private EntityManagerInterface $entityManager;
    private VideoRepository $videoRepository;

    public function __construct(
        EntityRepository $entityRepository,
        EntityManagerInterface $entityManager,
        VideoRepository $videoRepository
    ) {
        $this->entityRepository = $entityRepository;
        $this->entityManager = $entityManager;
        $this->videoRepository = $videoRepository;
    }

    #[Route('/entity/{id}', name: 'app.entity')]
    public function show(
        Request $request,
                $id
    ): Response {
        // Fetch entity by ID
        $entity = $this->entityRepository->find($id);

        // If entity not found, return a 404 response
        if (!$entity) {
            throw $this->createNotFoundException('Entity not found');
        }

        // Fetch related videos
        $videos = $this->videoRepository->findBy(['entity' => $entity]);
        $isMovie = false;
        if (!empty($videos)) {
            $isMovie = $videos[0]->isMovie();
        }

        // Fetch the Doctrine connection
        $connection = $this->entityManager->getConnection();

        // Create SeasonProvider instance and generate seasons HTML
        $seasons = $this->entityRepository->getSeasons($entity);

        $category = $this->entityManager->getRepository(Category::class)->find($entity->getCategoryId());

        // Render the template with the entity details, related videos, and seasons HTML
        return $this->render('entity/index.html.twig', [
            'entity' => $entity,
            'videos' => $videos,
            'isMovie' => $isMovie,
            'seasons' => $seasons,
            'category' => $category,
        ]);
    }

    #[Route('/entities', name: 'app.entities')]
    public function index(): Response
    {
        return $this->render('entity/watch.html.twig', [
            'controller_name' => 'EntityController',
        ]);
    }
}
