<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\SpecialisationDto;
use App\Entity\Specialisation;

class SpecialisationDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * @param Specialisation $specialisation
     */
    public function transformFromObject($specialisation): SpecialisationDto
    {
        return new SpecialisationDto(
            $specialisation->getId(),
            $specialisation->getName(),
        );
    }
}
