<?php

namespace App\Handler;

use App\Base\BaseHandler;
use App\Entity\Direction;

class AreaHandler extends BaseHandler
{
    public function changeFieldInResponse($area)
    {
        $description = $this->getDescriptionDirection($area);
        if (isset($area["COD_DIRECCION"])) {
            $area["DESCRIPCION_DIRECCION"] = $description;
            unset($area["COD_DIRECCION"]);
        }

        return $area;
    }

    public function getDescriptionDirection($area)
    {
        $direction = $this->entityManager->getRepository(Direction::class)->findOneBy(["id" => $area["COD_DIRECCION"]]);
        $description = $direction->getDescription();

        return $description;
    }
}
