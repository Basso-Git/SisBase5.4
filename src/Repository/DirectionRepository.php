<?php

namespace App\Repository;

use App\Entity\Direction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Direction>
 *
 * @method Direction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Direction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Direction[]    findAll()
 * @method Direction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Direction::class);
    }

    public function add(Direction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Direction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAll()
    {
        $sql = "SELECT * FROM p_direccion ORDER BY id DESC";
        return $sql;
    }

    public function insert($data, $lastId)
    {
        $description = $data["description"];
        $user = $data["userM"];

        $sql = "INSERT INTO p_direccion (id, descripcion, usuario_m, fecha_m) 
                    VALUES ($lastId, '$description', '$user', Sysdate)";
        return $sql;
    }

    public function update($data, $id)
    {
        $description = $data["description"];
        $user = $data["userM"];

        $sql = "UPDATE p_direccion SET descripcion = '$description', usuario_m = '$user', fecha_m = Sysdate
                    WHERE id = $id";

        return $sql;
    }
}
