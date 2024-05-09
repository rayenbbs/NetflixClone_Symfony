<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Video>
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    public function findNextVideo(Video $currentVideo): ?Video
    {
        $qb = $this->createQueryBuilder('v');
        $qb->where('v.entityId = :entityId')
           ->andWhere('v.id != :videoId')
           ->andWhere($qb->expr()->orX(
               $qb->expr()->andX(
                   $qb->expr()->eq('v.season', ':season'),
                   $qb->expr()->gt('v.episode', ':episode')
               ),
               $qb->expr()->gt('v.season', ':season')
           ))
           ->setParameter('entityId', $currentVideo->getEntityId())
           ->setParameter('season', $currentVideo->getSeasonNumber())
           ->setParameter('episode', $currentVideo->getEpisodeNumber())
           ->setParameter('videoId', $currentVideo->getId())
           ->orderBy('v.season')
           ->addOrderBy('v.episode')
           ->setMaxResults(1);

        $nextVideo = $qb->getQuery()->getResult();

        if (empty($nextVideo)) {
            $qb = $this->createQueryBuilder('v');
            $qb->where('v.season <= 1')
               ->andWhere('v.episode <= 1')
               ->andWhere('v.id != :videoId')
               ->setParameter('videoId', $currentVideo->getId())
               ->orderBy('v.views', 'DESC')
               ->setMaxResults(1);

            $nextVideo = $qb->getQuery()->getResult();
        }

        return empty($nextVideo) ? null : $nextVideo[0];
    }

    public function findNextVideoForUser(int $entityId, string $username): ?int
    {
        $qb = $this->createQueryBuilder('v');
        $qb->select('vp.videoId')
           ->innerJoin('App\Entity\VideoProgress', 'vp', 'WITH', 'vp.videoId = v.id')
           ->where('v.entityId = :entityId')
           ->andWhere('vp.username = :username')
           ->orderBy('vp.dateModified', 'DESC')
           ->setParameter('entityId', $entityId)
           ->setParameter('username', $username)
           ->setMaxResults(1);

        $videoId = $qb->getQuery()->getResult();

        if (empty($videoId)) {
            $qb = $this->createQueryBuilder('v');
            $qb->select('v.id')
               ->where('v.entityId = :entityId')
               ->setParameter('entityId', $entityId)
               ->orderBy('v.season')
               ->addOrderBy('v.episode')
               ->setMaxResults(1);

            $videoId = $qb->getQuery()->getResult();
        }

        return empty($videoId) ? null : $videoId[0];
    }

    public function hasSeen(Video $video, string $username): bool
    {
        $qb = $this->createQueryBuilder('v');
        $qb->select('COUNT(vp.id)')
           ->innerJoin('App\Entity\VideoProgress', 'vp', 'WITH', 'vp.videoId = v.id')
           ->where('vp.username = :username')
           ->andWhere('vp.videoId = :videoId')
           ->setParameter('username', $username)
           ->setParameter('videoId', $video->getId());

        return $qb->getQuery()->getSingleScalarResult() != 0;
    }
}
