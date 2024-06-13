<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\IllnessDto;
use App\Entity\Illness;
use App\Service\MedicamenteService;

class IllnessDtoTransformer extends AbstractResponseDtoTransformer
{
    public function __construct(private readonly MedicamenteService $medicamenteService)
    {
    }

    /**
     * @param Illness $illness
     */
    public function transformFromObject($illness): IllnessDto
    {
        return new IllnessDto(
            $illness->getId(),
            $illness->getName(),
            $illness->getCategory(),
            $this->medicamenteService->getMedicamenteFromIllness()
        );
    }
}
