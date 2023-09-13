<?php

namespace App\Repository;

use App\Entity\TopPosition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TopPosition>
 *
 * @method TopPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopPosition[]    findAll()
 * @method TopPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TopPosition::class);
    }

    public function add(TopPosition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TopPosition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
