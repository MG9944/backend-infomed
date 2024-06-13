<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\MedicamenteDto;
use App\Entity\Medicamente;

class MedicamenteDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * @param Medicamente $medicamente
     */
    public function transformFromObject($medicamente): MedicamenteDto
    {
        return new MedicamenteDto(
            $medicamente->getId(),
            $medicamente->getFullName(),
            $medicamente->getCategory(),
            $medicamente->getAtcCode(),
            $medicamente->getFigure(),
            $medicamente->getPackageContents()
        );
    }
}
