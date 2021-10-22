<?php

namespace App\Repository;

use App\Entity\MovieMeetings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @method MovieMeetings|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieMeetings|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieMeetings[]    findAll()
 * @method MovieMeetings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieMeetingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieMeetings::class);
    }

    public function save($movieMeetings)
    {
        if (!$movieMeetings instanceof MovieMeetings) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($movieMeetings)));
        }
        $this->_em->persist($movieMeetings);
        $this->_em->flush();
    }
    

    // /**
    //  * @return MovieMeetings[] Returns an array of MovieMeetings objects
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
    public function findOneBySomeField($value): ?MovieMeetings
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
