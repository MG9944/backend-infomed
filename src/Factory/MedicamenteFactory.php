<?php

namespace App\Factory;

use App\Controller\AbstractApiController;
use App\Entity\Medicamente;
use App\Exception\CreateMedicamenteException;
use App\Repository\MedicamenteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MedicamenteFactory extends AbstractApiController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ManagerRegistry $doctrine,
        private readonly HttpClientInterface $client,
        private readonly MedicamenteRepository $medicamenteRepository,
    ) {
    }

    /**
     * @throws CreateMedicamenteException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fetchMedicamente(): array
    {
        try {
            for ($page = 1; $page <= 4; ++$page) {
                $response = $this->client->request(
                    'GET',
                    'http://services.dlk24.pl/api/drugs/getDrugs/' . $page . '/100'
                );
                $context = $response->toArray();
            }
        } catch (\Exception $exception) {
            throw CreateMedicamenteException::problemWithConnectToExternalAPI();
        }

        return $context;
    }

    /**
     * @throws CreateMedicamenteException
     */
    public function createMedicamentes(array $medicamentes)
    {
        try {
            foreach ($medicamentes as $medicamente) {
                $medi = new Medicamente();
                $medi->setName($medicamente['nazwa']);
                $medi->setCategory($medicamente['rodzajPrep']);
                $medi->setAtcCode($medicamente['kodAtc']);
                $medi->setFullName($medicamente['nazPostDawka']);
                $medi->setFigure($medicamente['jednWielkOpak']);
                $medi->setPackageContents($medicamente['zawOpak']);
                $existingMedicamente = $this->medicamenteRepository
                    ->findOneBy(['fullName' => trim($medi->getName())]);
                if ($existingMedicamente) {
                    continue;
                }
                $this->entityManager->persist($medi);
                $this->entityManager->flush();
            }
        } catch (\Exception $exception) {
            throw CreateMedicamenteException::medicamenteNotCreated();
        }
    }
}
