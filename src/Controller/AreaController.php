<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Base\BaseController;
use App\Base\BaseHandler;
use App\Entity\Area;
use App\Entity\Direction;
use App\Handler\AreaHandler;

/**
 * @Route("/area")
 */
class AreaController extends BaseController
{
    const ENTITY_CLASS = Area::class;

    private $baseHandler;
    private $areaHandler;

    public function __construct(BaseHandler $baseHandler, AreaHandler $areaHandler, EntityManagerInterface $manager)
    {
        parent::__construct($manager);
        $this->baseHandler = $baseHandler;
        $this->areaHandler = $areaHandler;
    }

    /**
     * @Route("/", name="area_index")
     */
    public function index()
    {
        $directions = $this->entityManager->getRepository(Direction::class)->findBy([], ["description" => "ASC"]);

        return $this->render('area/index.twig', compact('directions'));
    }

    /**
     * @Route("/get_all", name="area_get_all")
     */
    public function getAll()
    {
        $areas = $this->baseHandler->getAllAsArray(self::ENTITY_CLASS);
        return $this->jsonResponse("success", 200, $areas);
        try {
            $areas = $this->baseHandler->getAllAsArray(self::ENTITY_CLASS);
            return $this->jsonResponse("success", 200, $areas);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }

    /**
     * @Route("/create", name="area_create")
     */
    public function create(Request $request)
    {
        try {
            $data = $request->request->all();
            $area = $this->baseHandler->saveRegister(self::ENTITY_CLASS, $data);
            $area = $this->areaHandler->changeFieldInResponse($area);
            return $this->jsonResponse("success", 200, $area);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }

    /**
     * @Route("/get/{id}", name="area_get")
     */
    public function getRegister($id)
    {
        try {
            $area = $this->findByIdObject($id, self::ENTITY_CLASS);
            return $this->successResponse($area);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }

    /**
     * @Route("/edit/{id}", name="area_edit")
     */
    public function edit(Request $request, $id)
    {
        try {
            $data = $request->request->all();
            $area = $this->baseHandler->saveRegister(self::ENTITY_CLASS, $data, $id);
            $area = $this->areaHandler->changeFieldInResponse($area);
            return $this->jsonResponse("success", 200, $area);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }

    /**
     * @Route("/delete/{id}", name="area_delete")
     */
    public function delete($id)
    {
        try {
            $areaDeleted = $this->baseHandler->getRegisterAsArray(self::ENTITY_CLASS, $id);
            $this->baseHandler->deleteRegister(self::ENTITY_CLASS, $id);

            return $this->jsonResponse("success", 200, $areaDeleted);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }
}
