<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Video;

class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    public function getUpNext(Video $currentVideo): ?Video
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('v')
            ->from('App\Entity\Video', 'v')
            ->where('v.entity = :entityId')
            ->andWhere('v.id != :videoId')
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('v.season', ':season'),
                        $queryBuilder->expr()->gt('v.episode', ':episode')
                    ),
                    $queryBuilder->expr()->gt('v.season', ':season')
                )
            )
            ->orderBy('v.season', 'ASC')
            ->addOrderBy('v.episode', 'ASC')
            ->setParameter('entityId', $currentVideo->getEntity())
            ->setParameter('season', $currentVideo->getSeason())
            ->setParameter('episode', $currentVideo->getEpisode())
            ->setParameter('videoId', $currentVideo->getId())
            ->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        if (empty($result)) {
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('v')
                ->from('App\Entity\Video', 'v')
                ->where('v.season <= :season')
                ->andWhere('v.episode <= 1')
                ->andWhere('v.id != :videoId')
                ->orderBy('v.views', 'DESC')
                ->setParameter('season', 1)
                ->setParameter('videoId', $currentVideo->getId())
                ->setMaxResults(1);

            $query = $queryBuilder->getQuery();
            $result = $query->getResult();
        }

        return empty($result) ? null : $result[0];
    }
}
