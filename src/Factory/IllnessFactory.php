<?php

namespace App\Factory;

use App\Controller\AbstractApiController;
use App\Entity\Illness;
use App\Exception\CreateIllnessException;
use App\Repository\MedicamenteRepository;
use Doctrine\Persistence\ManagerRegistry;

class IllnessFactory extends AbstractApiController
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly MedicamenteRepository $medicamenteRepository
    ) {
    }

    /**
     * @throws CreateIllnessException
     */
    public function createIllness(array $illnessData)
    {
        $illness = new Illness();
        $illness->setName($illnessData['name']);
        $illness->setCategory($illnessData['category']);

        $medicamente = $this->medicamenteRepository->findOneBy(['id' => $illnessData['medicamente']]);

        if (empty($medicamente)) {
            throw CreateIllnessException::medicamenteNotFound();
        }
        $illness->addMedicamente($medicamente);
        try {
            $manager = $this->doctrine->getManager();
            $manager->persist($illness);
            $manager->flush();
        } catch (\Exception $exception) {
            throw CreateIllnessException::illnessNotCreated();
        }
    }
}
