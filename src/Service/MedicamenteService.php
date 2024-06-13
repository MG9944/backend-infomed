<?php

namespace App\Service;

use App\Dto\Response\Transformer\MedicamenteDtoTransformer;
use App\Entity\Medicamente;
use App\Exception\CreateMedicamenteException;
use App\Repository\IllnessRepository;
use App\Repository\MedicamenteRepository;
use Doctrine\Persistence\ManagerRegistry;

class MedicamenteService
{
    public function __construct(
        private readonly MedicamenteDtoTransformer $medicamenteDtoTransformer,
        private readonly MedicamenteRepository $medicamenteRepository,
        private readonly ManagerRegistry $doctrine,
        private readonly IllnessRepository $illnessRepository
    ) {
    }

    public function getMedicamente(?array $sort = []): iterable
    {
        $medicamente = $this->medicamenteRepository->findAllSorted($sort);

        return $this->medicamenteDtoTransformer->transformFromObjects($medicamente);
    }

    public function editMedicamente(Medicamente $editMedicamente, $medicamenteId)
    {
        try {
            if (!$medicamente = $this->medicamenteRepository->find($medicamenteId)) {
                throw CreateMedicamenteException::medicamenteNotFound();
            }

            $medicamente->setName($editMedicamente->getName());
            $medicamente->setCategory($editMedicamente->getCategory());

            $manager = $this->doctrine->getManager();
            $manager->persist($medicamente);
            $manager->flush();
        } catch (\Exception $exception) {
            throw CreateMedicamenteException::medicamenteNotEdited();
        }
    }

    public function getMedicamenteFromIllness(): string
    {
        $array = [];
        $illnesses = $this->illnessRepository->findAll();
        foreach ($illnesses as $illness) {
            $medicamentes = $illness->getMedicamente();
            foreach ($medicamentes as $medicamente) {
                $array = $medicamente->getName();
            }
        }

        return $array;
    }
}
