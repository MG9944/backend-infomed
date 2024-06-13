<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Exception\CreateAppointmentException;
use App\Factory\AppointmentFactory;
use App\Form\AppointmentForm;
use App\Form\EditAppointmentForm;
use App\Repository\AppointmentRepository;
use App\Service\AppointmentService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends AbstractApiController
{
    public function __construct(
        private readonly AppointmentFactory $appointmentFactory,
        private readonly AppointmentService $appointmentService,
        private readonly AppointmentRepository $appointmentRepository,
        private readonly LoggerInterface $loggerMyApi,
    ) {
    }

    public function getAppointment(): Response
    {
        return $this->respond($this->appointmentService->getAppointments($this->getUser()));
    }

    public function createAppointment(Request $request): Response
    {
        try {
            $form = $this->buildForm(AppointmentForm::class);
            $form->handleRequest($request);
            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }

            $appointment = $form->getData();
            $this->appointmentFactory->createAppointment($appointment, $this->getUser());
        } catch (CreateAppointmentException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'Appointment was created'], Response::HTTP_CREATED);
    }

    public function editAppointment(Request $request): Response
    {
        try {
            $form = $this->buildForm(AppointmentForm::class);
            $form->handleRequest($request);
            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }
            $appointment = $form->getData();
            $this->appointmentService->editAppointment($appointment, $request->get('id'), $this->getUser());
        } catch (CreateAppointmentException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'Appointment was edited'], Response::HTTP_CREATED);
    }

    public function cancelPatientAppointment(Request $request): Response
    {
        try {
            $id = $request->get('id');
            $this->appointmentService->cancelAppointment($id);

            return new JsonResponse(['message' => 'Appointment has been cancelled'], Response::HTTP_OK);
        } catch (CreateAppointmentException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function continueInsertAppointmentData(Request $request): Response
    {
        try {
            $id = $request->get('id');
            $appointment = $this->appointmentRepository->find($id);
            $form = $this->buildForm(EditAppointmentForm::class, $appointment, [
                'method' => $request->getMethod(),
            ]);
            $form->handleRequest($request);
            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }
            /** @var Appointment $appointment */
            $appointment = $form->getData();
            $this->appointmentService->continueInsertAppointmentData($appointment, $id);
        } catch (CreateAppointmentException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'Appointment data was successful inserted'], Response::HTTP_CREATED);
    }
}
