<?php

namespace App\Handler;

use App\Base\BaseHandler;
use App\Entity\Direction;

class TopPositionHandler extends BaseHandler
{
    public function changeFieldInResponse($topPosition)
    {
        $description = $this->getDescriptionDirection($topPosition);
        if (isset($topPosition["COD_DIRECCION"])) {
            $topPosition["DESCRIPCION_DIRECCION"] = $description;
            unset($topPosition["COD_DIRECCION"]);
        }

        return $topPosition;
    }

    public function getDescriptionDirection($topPosition)
    {
        $direction = $this->entityManager->getRepository(Direction::class)->findOneBy(["id" => $topPosition["COD_DIRECCION"]]);
        $description = $direction->getDescription();

        return $description;
    }
}
