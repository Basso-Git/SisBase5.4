<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Base\BaseController;
use App\Base\BaseHandler;
use App\Entity\Direction;

/**
 * @Route("/direction")
 */
class DirectionController extends BaseController
{
    const ENTITY_CLASS = Direction::class;

    /**
     * @Route("/", name="direction_index")
     */
    public function index()
    {
        //TODO: Add breadcrumbs with bootstrap
        return $this->render('direction/index.twig');
    }

    /**
     * @Route("/get_all", name="direction_get_all")
     */
    public function getAll(BaseHandler $handler)
    {
        try {
            $directions = $handler->getAllAsArray(self::ENTITY_CLASS);
            return $this->jsonResponse("success", 200, $directions);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }

    /**
     * @Route("/create", name="direction_create")
     */
    public function create(Request $request, BaseHandler $handler)
    {
        try {
            $data = $request->request->all();
            $direction = $handler->saveRegister(self::ENTITY_CLASS, $data);
            return $this->jsonResponse("success", 200, $direction);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }

    /**
     * @Route("/get/{id}", name="direction_get")
     */
    public function getRegister($id)
    {
        try {
            //Este lo busco como OBJETO y no via SQL(array) porque necesito que al recuperar los datos y completarlos en el modal EDITAR,
            //estos campos coincidan los nombres (EN INGLES) respecto a los names de los INPUT. 
            //Sino tendria que en el HTML en cada NAME de los inputs, ponerlos en español y en mayus
            $direction = $this->findByIdObject($id, self::ENTITY_CLASS);
            return $this->successResponse($direction);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }

    /**
     * @Route("/edit/{id}", name="direction_edit")
     */
    public function edit(Request $request, BaseHandler $handler, $id)
    {
        try {
            $data = $request->request->all();
            $direction = $handler->saveRegister(self::ENTITY_CLASS, $data, $id);
            return $this->jsonResponse("success", 200, $direction);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }

    /**
     * @Route("/delete/{id}", name="direction_delete")
     */
    public function delete(BaseHandler $baseHandler, $id)
    {
        try {
            $directionDeleted = $baseHandler->getRegisterAsArray(self::ENTITY_CLASS, $id);
            $baseHandler->deleteRegister(self::ENTITY_CLASS, $id);

            return $this->jsonResponse("success", 200, $directionDeleted);
        } catch (\Exception $e) {
            return $this->jsonResponse("error", 500, null, $e->getMessage());
        }
    }
}
