<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Base\BaseController;
use App\Base\BaseHandler;
use App\Entity\Direction;
use App\Entity\TopPosition;
use App\Handler\TopPositionHandler;


/**
 * @Route("/top_position")
 */
class TopPositionController extends BaseController
{
    const ENTITY_CLASS = TopPosition::class;

    private $baseHandler;
    private $topPositionHandler;

    public function __construct(BaseHandler $baseHandler, TopPositionHandler $topPositionHandler, EntityManagerInterface $manager)
    {
        parent::__construct($manager);

        $this->baseHandler = $baseHandler;
        $this->topPositionHandler = $topPositionHandler;
    }

    /**
     * @Route("/create", name="top_position_index")
     */
    public function index()
    {
        //TODO: Add breadcrumbs with bootstrap
        $directions = $this->entityManager->getRepository(Direction::class)->findBy([], ["description" => "ASC"]);

        return $this->render('top_position/index.twig', compact('directions'));
    }

    /**
     * @Route("/create", name="top_position_create")
     */
    public function create(Request $request)
    {
        try {
            $data = $request->request->all();
            $topPosition = $this->baseHandler->saveRegister(self::ENTITY_CLASS, $data);
            $topPosition = $this->topPositionHandler->changeFieldInResponse($topPosition);
            return $this->jsonResponse("success", 200, $topPosition);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }

    /**
     * @Route("/get/{id}", name="top_position_get")
     */
    public function getRegister($id)
    {
        try {
            $topPosition = $this->findByIdObject($id, self::ENTITY_CLASS);
            return $this->successResponse($topPosition);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }

    /**
     * @Route("/get_all", name="top_position_get_all")
     */
    public function getAll()
    {
        try {
            $topPositions = $this->baseHandler->getAllAsArray(self::ENTITY_CLASS);
            return $this->jsonResponse("success", 200, $topPositions);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }

    /**
     * @Route("/edit/{id}", name="top_position_edit")
     */
    public function edit(Request $request, $id)
    {
        try {
            $data = $request->request->all();
            $topPosition = $this->baseHandler->saveRegister(self::ENTITY_CLASS, $data, $id);
            $topPosition = $this->topPositionHandler->changeFieldInResponse($topPosition);
            return $this->jsonResponse("success", 200, $topPosition);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }

    /**
     * @Route("/delete", name="top_position_delete")
     */
    public function delete($id)
    {
        try {
            $topPositionDeleted = $this->baseHandler->getRegisterAsArray(self::ENTITY_CLASS, $id);
            $this->baseHandler->deleteRegister(self::ENTITY_CLASS, $id);

            return $this->jsonResponse("success", 200, $topPositionDeleted);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }
}
