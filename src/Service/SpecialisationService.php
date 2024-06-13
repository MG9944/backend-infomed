<?php

namespace App\Service;

use App\Dto\Response\Transformer\SpecialisationDtoTransformer;
use App\Repository\SpecialisationRepository;

class SpecialisationService
{
    public function __construct(
        private readonly SpecialisationDtoTransformer $specialisationDtoTransformer,
        private readonly SpecialisationRepository $specialisationRepository,
    ) {
    }

    public function getSpecialisations(): iterable
    {
        $specialisations = $this->specialisationRepository->findBy([], ['name' => 'ASC']);

        return $this->specialisationDtoTransformer->transformFromObjects($specialisations);
    }
}
