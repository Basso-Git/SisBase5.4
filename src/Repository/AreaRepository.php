<?php

namespace App\Repository;

use App\Entity\Area;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Area>
 *
 * @method Area|null find($id, $lockMode = null, $lockVersion = null)
 * @method Area|null findOneBy(array $criteria, array $orderBy = null)
 * @method Area[]    findAll()
 * @method Area[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Area::class);
    }

    public function add(Area $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Area $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAll()
    {
        $sql = "SELECT a.*, d.descripcion AS descripcion_direccion
        FROM p_area a
        JOIN p_direccion d ON a.cod_direccion = d.id";
        return $sql;
    }

    public function insert($data, $lastId)
    {
        $description = $data["description"];
        $user = $data["userM"];
        $directionid = $data["directionId"];

        $sql = "INSERT INTO p_area (id, descripcion, cod_direccion, usuario_m, fecha_m) 
                    VALUES ($lastId, '$description', $directionid, '$user', Sysdate)";
        return $sql;
    }

    public function update($data, $id)
    {
        $description = $data["description"];
        $user = $data["userM"];
        $directionid = $data["directionId"];

        $sql = "UPDATE p_area SET descripcion = '$description', cod_direccion = $directionid, usuario_m = '$user', fecha_m = Sysdate
                    WHERE id = $id";

        return $sql;
    }
}
