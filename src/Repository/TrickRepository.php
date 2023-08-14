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
    public function getTricks(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT *
            FROM Trick t
        ';

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative();
    }

    /**
     *
     */
    public function findTrickBySlug(string $slug): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT t.*, gr.name group_name, u.username
            FROM Trick t
            INNER JOIN `Group` gr ON gr.id = t.group_id
            INNER JOIN User u ON u.id = t.user_id
            WHERE t.slug = :slug
            ';

        $resultSet = $conn->executeQuery($sql, ['slug' => $slug]);

        return $resultSet->fetchAllAssociative();
    }
}
