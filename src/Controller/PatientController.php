<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Exception\CreateDoctorException;
use App\Exception\CreatePatientException;
use App\Exception\GeneratePDFException;
use App\Factory\PatientFactory;
use App\Form\PatientForm;
use App\Repository\PatientRepository;
use App\Service\PatientCardService;
use App\Service\PatientService;
use App\Service\PDFFIleService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientController extends AbstractApiController
{
    public function __construct(
        private readonly PatientFactory $patientFactory,
        private readonly PatientService $patientService,
        private readonly PatientRepository $patientRepository,
        private readonly PatientCardService $patientCardService,
        private readonly PDFFIleService $PDFFIleService,
        private readonly LoggerInterface $loggerMyApi,
    ) {
    }

    public function getPatientsByMedicalCenter(Request $request): Response
    {
        return $this->respond($this->patientService->getPatientsByMedicalCenter($this->getUser(), $request->get('orderBy', [])));
    }

    public function createPatient(Request $request): Response
    {
        try {
            $form = $this->buildForm(PatientForm::class);
            $form->handleRequest($request);
            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }
            /** @var Patient $patient */
            $patient = $form->getData();
            $this->patientFactory->createPatient($patient, $this->getUser());
        } catch (CreateDoctorException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'Patient registration was successful'], Response::HTTP_CREATED);
    }

    public function editPatient(Request $request): Response
    {
        try {
            $id = $request->get('id');
            $patient = $this->patientRepository->find($id);
            $form = $this->buildForm(PatientForm::class, $patient, [
                'method' => $request->getMethod(),
            ]);
            $form->handleRequest($request);
            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }
            /** @var Patient $patient */
            $patientData = $form->getData();
            $this->patientService->editPatient($patientData, $patient, $this->getUser());
        } catch (CreateDoctorException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'Patient edit was successful'], Response::HTTP_CREATED);
    }

    public function getPatientsInPatientCard(Request $request): Response
    {
        return $this->respond($this->patientCardService->getPatientInfoInPatientCard($this->getUser(), $request->get('orderBy', [])));
    }

    public function getPatientAppoinmentsInPatientCard(Request $request): Response
    {
        try {
            return $this->respond($this->patientCardService->getPatientAppointmentsInPatientCard($request->get('id')));
        } catch (CreatePatientException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function generatePrescription(Request $request): Response
    {
        try {
            $this->PDFFIleService->generateRecieptionPDF($request->get('id'), $this->getUser());
        } catch (GeneratePDFException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'Prescription generate was successful'], Response::HTTP_CREATED);
    }

    public function generatePatientCard(Request $request): Response
    {
        try {
            $this->PDFFIleService->generatePatientCard($request->get('idPatient'), $this->getUser());
        } catch (GeneratePDFException|CreatePatientException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'Prescription generate was successful'], Response::HTTP_CREATED);
    }
}
