<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trick>
 *
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

   /**
    * @return Trick[] Returns an array of Trick objects
    */
   public function findAllByDate(): array
   {
       return $this->createQueryBuilder('t')
            ->orderBy('t.modifiedAt', 'DESC')
            ->getQuery()
            ->getResult()
       ;
   }

   public function findLastTrick()
   {
       return $this->createQueryBuilder('t')
           ->orderBy('t.id', 'DESC')
           ->setMaxResults(1)
           ->getQuery()
           ->getOneOrNullResult();
   }

}
