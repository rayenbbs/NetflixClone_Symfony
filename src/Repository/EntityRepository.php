<?php

namespace App\Repository;

use App\Entity\Entity;
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
            $result->innerJoin('e.videos','v')
                ->where('v.isMovie = :isMovie')
                ->setParameter('isMovie',0);
            if($categoryId!==null){
                $result->andWhere('e.category = :categoryId')
                    ->setParameter('categoryId',$categoryId);
            }
            $result->setMaxResults($limit);
            return $result->getQuery()
                ->getResult();
       }

       public function getMovieEntities($categoryId,$limit){
            $result=$this->createQueryBuilder('e');
            $result->innerJoin('e.videos','v')
                ->where('v.isMovie = :isMovie')
                ->setParameter('isMovie',1);
           if($categoryId!==null){
               $result->andWhere('e.category = :categoryId')
                   ->setParameter('categoryId',$categoryId);
           }
           $result
               ->setMaxResults($limit);
           return $result->getQuery()
               ->getResult();
    }
    public function findByTerm(string $term, int $limit = 30): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.name LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
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
