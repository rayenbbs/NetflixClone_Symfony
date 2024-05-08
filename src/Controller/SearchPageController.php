<?php

namespace App\Controller;

use App\Service\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchPageController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function searchPage(): Response
    {
        // Render the search page
        return $this->render('search/index.html.twig');
    }

    #[Route('/search/results', name: 'search_results', methods: ['POST'])]
    public function search(Request $request, SearchService $searchService): Response
    {
        $term = $request->request->get('term'); // Get search term from POST request

        if (empty($term)) {
            return new Response("", Response::HTTP_NO_CONTENT); // Return empty response if term is blank
        }

        // Get search results from the service
        $results = $searchService->getSearchResults($term);

        // Render a Twig template with the results
        return $this->render('search/results.html.twig', [
            'results' => $results,
        ]);
    }
}