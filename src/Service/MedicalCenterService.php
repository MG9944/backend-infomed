<?php

namespace App\Service;

use App\Dto\Response\Transformer\MedicalCenterDtoTransformer;
use App\Repository\MedicalCenterRepository;

class MedicalCenterService
{
    public function __construct(
        private readonly MedicalCenterDtoTransformer $medicalCenterDtoTransformer,
        private readonly MedicalCenterRepository $medicalCenterRepository,
    ) {
    }

    public function getMedicalCenter(): iterable
    {
        $medicalCenters = $this->medicalCenterRepository->findBy([], ['name' => 'ASC']);

        return $this->medicalCenterDtoTransformer->transformFromObjects($medicalCenters);
    }
}
