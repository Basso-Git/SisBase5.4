<?php

namespace AppBundle\Base;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;


class BaseController extends AbstractController
{
    public $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function errorResponse($code = 400, $message = null)
    {
        $response = new JsonResponse();
        $response->setData([
            "status"    => 'error',
            "code"      => $code,
            "message"   => $message
        ]);

        return $response;
    }

    //Esta funcion la uso cuando tengo que devolver un objeto al front (osea al ajax)
    public function successResponse($data)
    {
        $response = new JsonResponse();
        $response->setData([
            "status"    => 'success',
            "code"      => 200,
            "data"      => json_decode($this->serializer($data)->getContent(), true)
        ]);

        return $response;
    }

    public function findByIdObject($id, $entity)
    {
        $entityFinded = $this->entityManager->getRepository($entity)->findOneBy(["id" => $id]);
        if (is_null($entityFinded)) {
            throw new \Exception("Registro no encontrado");
        }

        return $entityFinded;
    }

    function jsonResponse($status, $code, $data = null, $error = null): JsonResponse
    {
        $response = [
            'status' => $status,
            'code'   => $code,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($error !== null) {
            $response['error'] = $error;
        }

        return new JsonResponse($response);
    }

    public function repository($entityName)
    {
        return $this->entityManager->getRepository(
            $this->getParameter("entity_route") . $entityName
        );
    }


    public function serializer($data)
    {
        $normalizers    = array(new GetSetMethodNormalizer());
        $encoders       = array("json" => new JsonEncoder());

        $serializer     = new Serializer($normalizers, $encoders);
        $json           = $serializer->serialize($data, "json");

        $response       = new Response();
        $response->setContent($json);
        $response->headers->set("Content-Type", "application/json");

        return $response;
    }
}
