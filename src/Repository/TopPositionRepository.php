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

    public function getAll()
    {
        $sql = "SELECT ps.*, d.descripcion AS descripcion_direccion
        FROM p_puesto_superior ps
        JOIN p_direccion d ON ps.cod_direccion = d.id";
        return $sql;
    }

    public function insert($data, $lastId)
    {
        $description = $data["description"];
        $user = $data["userM"];
        $directionid = $data["directionId"];

        $sql = "INSERT INTO p_puesto_superior (id, descripcion, cod_direccion, usuario_m, fecha_m) 
                    VALUES ($lastId, '$description', $directionid, '$user', Sysdate)";
        return $sql;
    }

    public function update($data, $id)
    {
        $description = $data["description"];
        $user = $data["userM"];
        $directionid = $data["directionId"];

        $sql = "UPDATE p_puesto_superior SET descripcion = '$description', cod_direccion = $directionid, usuario_m = '$user', fecha_m = Sysdate
                    WHERE id = $id";

        return $sql;
    }
}
