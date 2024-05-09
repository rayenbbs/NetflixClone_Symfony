<?php

namespace App\Repository;

use App\Entity\Entity;
use App\Entity\Video;
use App\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Entity>
 */
class EntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entity::class);
    }

    /**
     * @return Entity[] Returns an array of Entity objects
     */
    public function getEntities($categoryId,$limit): array
    {
        $result = $this->createQueryBuilder('e');
        if($categoryId!=null){
            $result->andWhere('e.categoryId = :val')->setParameter('val', $categoryId);
        }
        return  $result
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getSearchEntities($term){
        $result=$this->createQueryBuilder('e');
        $result->andWhere($result->expr()->like('e.name',':term'))
            ->setParameter('term','%'.$term.'%')
            ->setMaxResults(30);
        return $result->getQuery()->getResult();
    }

    public function getTVShowEntities($categoryId,$limit){
        $result=$this->createQueryBuilder('e');
        $result->select('DISTINCT(e.id)')
            ->innerJoin('e.videos','v')
            ->where('v.isMovie = :isMovie')
            ->setParameter('isMovie',0);
        if($categoryId!==null){
            $result->andWhere('e.category = :categoryId')
                ->setParameter('categoryId',$categoryId);
        }
        $result->orderBy('RAND()')
            ->setMaxResults($limit);
        return $result->getQuery()
            ->getResult();
    }

    public function getMovieEntities($categoryId,$limit){
        $result=$this->createQueryBuilder('e');
        $result->select('DISTINCT(e.id)')
            ->innerJoin('e.videos','v')
            ->where('v.isMovie = :isMovie')
            ->setParameter('isMovie',1);
        if($categoryId!==null){
            $result->andWhere('e.category = :categoryId')
                ->setParameter('categoryId',$categoryId);
        }
        $result->orderBy('RAND()')
            ->setMaxResults($limit);
        return $result->getQuery()
            ->getResult();
    }

    public function getSeasons(Entity $entity): array
    {
        $entityManager = $this->getEntityManager();

        // Get the ID of the entity
        $entityId = $entity->getId();

        // Create a query builder
        $queryBuilder = $entityManager->createQueryBuilder();

        // Build the query to fetch videos associated with the entity, grouped by seasons
        $queryBuilder->select('v')
            ->from(Video::class, 'v')
            ->where('v.entity = :entityId')
            ->andWhere('v.isMovie = :isMovie')
            ->orderBy('v.season')
            ->addOrderBy('v.episode')
            ->setParameter('entityId', $entityId)
            ->setParameter('isMovie', false);

        $videos = $queryBuilder->getQuery()->getResult();

        // Group the videos by seasons
        $seasons = [];
        foreach ($videos as $video) {
            $seasonNumber = $video->getSeason();
            if (!isset($seasons[$seasonNumber])) {
                $seasons[$seasonNumber] = [];
            }
            $seasons[$seasonNumber][] = $video;
        }

        // Create Season objects from the grouped videos
        $seasonObjects = [];
        foreach ($seasons as $seasonNumber => $videos) {
            $seasonObjects[] = new Season($seasonNumber, $videos);
        }

        return $seasonObjects;
    }

    //    /**
    //     * @return Entity[] Returns an array of Entity objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Entity
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}