<?php

namespace App\Service;



use App\Repository\EntityRepository;

class SearchService
{
    private EntityRepository $entityRepository; // Repository to get entities

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    public function getSearchResults(string $term): array
    {
        // Perform the search using the repository
        // Define the method findByTerm in your repository
        return $this->entityRepository->findByTerm($term);
    }
}
