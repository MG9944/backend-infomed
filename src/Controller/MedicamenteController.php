<?php

namespace App\Controller;

use App\Entity\Medicamente;
use App\Exception\CreateMedicamenteException;
use App\Factory\MedicamenteFactory;
use App\Form\MedicamenteForm;
use App\Repository\MedicamenteRepository;
use App\Service\MedicamenteService;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MedicamenteController extends AbstractApiController
{
    public function __construct(
        private readonly MedicamenteFactory $medicamenteFactory,
        private readonly MedicamenteService $medicamenteService,
        private readonly MedicamenteRepository $medicamenteRepository,
        private readonly LoggerInterface $loggerMyApi,
    ) {
    }

    public function getMedicamente(Request $request): Response
    {
        return $this->respond($this->medicamenteService->getMedicamente($request->get('orderBy', [])));
    }

    #[IsGranted('ROLE_ADMIN')]
    public function createMedicamente(Request $request): Response
    {
        try {
            $medicamente = $this->medicamenteFactory->fetchMedicamente();
            $this->medicamenteFactory->createMedicamentes($medicamente);
        } catch (CreateMedicamenteException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'Medicamente was created'], Response::HTTP_CREATED);
    }

    #[IsGranted('ROLE_ADMIN')]
    public function editMedicamente(Request $request): Response
    {
        try {
            $id = $request->get('id');
            $medicamente = $this->medicamenteRepository->find($id);
            $form = $this->buildForm(MedicamenteForm::class, $medicamente, [
                'method' => $request->getMethod(),
            ]);
            $form->handleRequest($request);
            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }

            /** @var Medicamente $medicamente */
            $medicamenteData = $form->getData();
            $this->medicamenteService->editMedicamente($medicamenteData, $medicamente);
        } catch (CreateMedicamenteException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'Medciamente was edited'], Response::HTTP_CREATED);
    }
}
