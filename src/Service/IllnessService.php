<?php

namespace App\Service;

use App\Dto\Response\Transformer\IllnessDtoTransformer;
use App\Exception\CreateIllnessException;
use App\Exception\CreateMedicamenteException;
use App\Repository\IllnessRepository;
use App\Repository\MedicamenteRepository;
use Doctrine\Persistence\ManagerRegistry;

class IllnessService
{
    public function __construct(
        private readonly IllnessDtoTransformer $illnessDtoTransformer,
        private readonly IllnessRepository $illnessRepository,
        private readonly ManagerRegistry $doctrine,
        private readonly MedicamenteRepository $medicamenteRepository
    ) {
    }

    public function getIllness(?array $sort): iterable
    {
        $illnesses = $this->illnessRepository->findAllSorted($sort);

        return $this->illnessDtoTransformer->transformFromObjects($illnesses);
    }

    /**
     * @throws CreateIllnessException
     */
    public function editIllness(array $illnessData, $illnessId)
    {
        try {
            if (!$illness = $this->illnessRepository->find($illnessId)) {
                throw CreateMedicamenteException::medicamenteNotFound();
            }

            $illness->setName($illnessData['name']);
            $illness->setCategory($illnessData['category']);

            $medicamente = $this->medicamenteRepository->findOneBy(['id' => $illnessData['medicamente']]);

            if (empty($medicamente)) {
                throw CreateIllnessException::medicamenteNotFound();
            }
            $illness->addMedicamente($medicamente);

            $manager = $this->doctrine->getManager();
            $manager->persist($illness);
            $manager->flush();
        } catch (\Exception $exception) {
            throw CreateIllnessException::illnessNotEdited();
        }
    }
}
