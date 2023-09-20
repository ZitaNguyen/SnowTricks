<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

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
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator
    )
    {
        parent::__construct($registry, Trick::class);
    }

   /**
    * Get tricks ordered by date
    * @param int $page
    * @return PaginationInterface
    */
   public function findAllByDate(int $page): PaginationInterface
   {
       $data = $this->createQueryBuilder('t')
            ->orderBy('t.modifiedAt', 'DESC')
            ->getQuery()
            ->getResult()
       ;

       $tricks = $this->paginator->paginate($data, $page, 8);

       return $tricks;
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
