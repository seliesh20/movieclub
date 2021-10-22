<?php

namespace App\Repository;

use App\Entity\MovieList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovieList|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieList|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieList[]    findAll()
 * @method MovieList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieList::class);
    }
    
    public function findRandomUnSeenMovie()
    {
        $this->createQueryBuilder('ml')
            //->select('ml')
            //->Join('mm.movie_list', 'ml')
            ->where("ml.id not in (select distinct movie_list_id from MovieMeetings where movie_list_id is not null)")
            //->orderBy('RAND()')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return MovieList[] Returns an array of MovieList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MovieList
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
