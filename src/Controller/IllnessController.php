<?php

namespace App\Controller;

use App\Exception\CreateIllnessException;
use App\Factory\IllnessFactory;
use App\Form\IllnessForm;
use App\Repository\IllnessRepository;
use App\Service\IllnessService;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IllnessController extends AbstractApiController
{
    public function __construct(
        private readonly IllnessFactory $illnessFactory,
        private readonly IllnessService $illnessService,
        private readonly IllnessRepository $illnessRepository,
        private readonly LoggerInterface $loggerMyApi,
    ) {
    }

    public function getIllness(Request $request): Response
    {
        return $this->respond($this->illnessService->getIllness($request->get('orderBy', [])));
    }

    #[IsGranted('ROLE_ADMIN')]
    public function createIllness(Request $request): Response
    {
        try {
            $form = $this->buildForm(IllnessForm::class);
            $form->handleRequest($request);
            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }

            $illness = $form->getData();
            $this->illnessFactory->createIllness($illness);
        } catch (CreateIllnessException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'Illness was created'], Response::HTTP_CREATED);
    }


    public function editIllness(Request $request): Response
    {
        try {
            $id = $request->get('id');
            $illness = $this->illnessRepository->find($id);
            $form = $this->buildForm(IllnessForm::class);
            $form->handleRequest($request);
            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }

            $illnessData = $form->getData();
            $this->illnessService->editIllness($illnessData, $illness);
        } catch (CreateIllnessException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'Illness was edited'], Response::HTTP_CREATED);
    }
}
