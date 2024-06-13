<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\MedicalCenterDto;
use App\Entity\MedicalCenter;

class MedicalCenterDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * @param MedicalCenter $medicalCenter
     */
    public function transformFromObject($medicalCenter): MedicalCenterDto
    {
        return new MedicalCenterDto(
            $medicalCenter->getId(),
            $medicalCenter->getName(),
            $medicalCenter->getFullAddress(),
            $medicalCenter->getNip(),
        );
    }
}
