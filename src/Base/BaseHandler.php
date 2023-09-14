<?php

namespace App\Base;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseHandler extends AbstractController
{
    public $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getMaxIdSQL($table)
    {
        $connection = $this->entityManager->getConnection();
        $statement = $connection->prepare(
            "SELECT NVL(MAX(id), 0) + 1 AS lastId FROM $table"
        );

        $statement->executeQuery();
        $result = $statement->executeStatement();

        return intval($result[0]["LASTID"]);
    }

    public function getAllAsArray($entity)
    {
        $repository = $this->entityManager->getRepository($entity);
        $sql = $repository->getAll();

        $connection = $this->entityManager->getConnection();
        $statement  = $connection->prepare($sql);

        $result = $statement->executeQuery();
        $data = $result->fetchAllAssociative();

        return $data;
    }

    public function getRegisterAsArray($entity, $id = null)
    {
        $table = $entity::TABLE_NAME;

        $connection = $this->entityManager->getConnection();
        $statement  = $connection->prepare(
            $id == null
                ? "SELECT * FROM $table WHERE id = (SELECT MAX(id) FROM $table)"
                : "SELECT * FROM $table WHERE id = $id"
        );

        $result = $statement->executeQuery();
        $register = $result->fetchOne();

        return $register;
    }

    public function deleteRegister($entity, $id)
    {
        $register = $this->entityManager->getRepository($entity)->find($id);
        $this->entityManager->remove($register);
        $this->entityManager->flush();
    }

    public function saveRegister($entity, $data, $id = null)
    {
        //Accedo a la constante declarada en la entidad
        $tableName = $entity::TABLE_NAME;

        $repository = $this->entityManager->getRepository($entity);
        $sql = "";
        if (is_null($id)) {
            $lastId = $this->getMaxIdSQL($tableName);
            $sql = $repository->insert($data, $lastId);
        } else {
            $data["userM"] = $this->getUser();
            $sql = $repository->update($data, $id);
        }

        $this->setWithSQL($sql);
        $recordToReturn = $this->getRegisterAsArray($entity, $id);

        return $recordToReturn;
    }

    public function setWithSQL($sql)
    {
        $connection = $this->entityManager->getConnection();
        $statement  = $connection->prepare($sql);

        $statement->executeQuery();
    }
}
