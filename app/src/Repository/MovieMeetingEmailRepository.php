<?php

namespace App\Repository;

use App\Entity\MovieMeetingEmail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Exception\UnsupportedException;

/**
 * @method MovieMeetingEmail|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieMeetingEmail|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieMeetingEmail[]    findAll()
 * @method MovieMeetingEmail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieMeetingEmailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieMeetingEmail::class);
    }
    public function save($movieMeetingEmail)
    {
        if (!$movieMeetingEmail instanceof MovieMeetingEmail) {
            throw new UnsupportedException(sprintf('Instances of "%s" are not supported.', \get_class($movieMeetingEmail)));
        }
        $this->_em->persist($movieMeetingEmail);
        $this->_em->flush();
    }

    // /**
    //  * @return MovieMeetingEmail[] Returns an array of MovieMeetingEmail objects
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
    public function findOneBySomeField($value): ?MovieMeetingEmail
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
