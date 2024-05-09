<?php

namespace App\Controller;

use App\Entity\Video;
use App\Repository\VideoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class VideoController extends AbstractController
{
    private VideoRepository $videoRepository;
    private ManagerRegistry $doctrine;
    private TokenStorageInterface $tokenStorage;

    public function __construct(VideoRepository $videoRepository, ManagerRegistry $doctrine, TokenStorageInterface $tokenStorage)
    {
        $this->videoRepository = $videoRepository;
        $this->doctrine = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }

    #[Route('entity/watch/{id}', name: 'app.video')]
    public function watchVideo(Request $request, int $id): Response
    {
        $video = $this->videoRepository->find($id);

        if (!$video) {
            throw $this->createNotFoundException('Video not found');
        }

        $video->incrementViews();
        $this->doctrine->getManager()->flush();

        // Get the currently logged-in user
        //$user = $this->tokenStorage->getToken()->getUser();

        $upNextVideo = $this->videoRepository->getUpNext($video);

        return $this->render('video/watch.html.twig', [
            'video' => $video,
            'upNextVideo' => $upNextVideo,
            'hideNav' => true,
            // 'user' => $user, // Pass the user information to the Twig template
        ]);
    }
}
