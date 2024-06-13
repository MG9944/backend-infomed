<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\ChangeDoctorAccountStatus;
use App\Exception\CreateDoctorException;
use App\Factory\DoctorFactory;
use App\Form\CreateNewDoctorForm;
use App\Form\DoctorForm;
use App\Form\EditDoctorForm;
use App\Form\EditPresonalDataForm;
use App\Repository\UserRepository;
use App\Service\MedicalCenterService;
use App\Service\SpecialisationService;
use App\Service\UserService;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractApiController
{
    public function __construct(
        private readonly DoctorFactory $doctorFactory,
        private readonly MedicalCenterService $medicalCenterService,
        private readonly SpecialisationService $specialisationService,
        private readonly UserService $userService,
        private readonly UserRepository $userRepository,
        private readonly LoggerInterface $loggerMyApi,
    ) {
    }

    public function registerDoctor(Request $request): Response
    {
        try {
            $form = $this->buildForm(DoctorForm::class);
            $form->handleRequest($request);
            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }

            $user = $form->getData();
            $this->doctorFactory->createDoctor($user);
        } catch (CreateDoctorException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'Registration was successful'], Response::HTTP_CREATED);
    }

    #[IsGranted('ROLE_ADMIN')]
    public function createNewDoctor(Request $request): Response
    {
        try {
            $form = $this->buildForm(CreateNewDoctorForm::class);
            $form->handleRequest($request);
            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }

            $user = $form->getData();
            $this->doctorFactory->createNewDoctor($user, $this->getUser());
        } catch (CreateDoctorException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['message' => 'Registration was successful'], Response::HTTP_CREATED);
    }

    public function getMedicalCenter(): Response
    {
        return $this->respond($this->medicalCenterService->getMedicalCenter());
    }

    public function getSpecialisation(): Response
    {
        return $this->respond($this->specialisationService->getSpecialisations());
    }

    public function getDoctorsByMedicalCenter(Request $request): Response
    {
        return $this->respond($this->userService->getDoctorsByMedicalCenter($this->getUser(), $request->get('orderBy', [])));
    }

    public function getUserInfo(): Response
    {
        return new JsonResponse([
            'firstname' => $this->getUser()->getFirstName(),
            'lastname' => $this->getUser()->getLastName(),
            'email' => $this->getUser()->getEmail(),
            'phoneNumber' => $this->getUser()->getPhoneNumber(),
            'roles' => $this->getUser()->getRoles(),
        ], Response::HTTP_OK);
    }

    public function changeStatus(Request $request): Response
    {
        try {
            $this->userService->changeDoctorStatus($request->get('userId'));
        } catch (ChangeDoctorAccountStatus  $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(
            ['message' => sprintf('Changed status of doctor account with id %d', $request->get('userId'))],
            Response::HTTP_OK
        );
    }

    #[IsGranted('ROLE_ADMIN')]
    public function editUser(Request $request): Response
    {
        try {
            $doctorId = $this->userRepository->find($request->get('userId'));
            $form = $this->buildForm(CreateNewDoctorForm::class);
            $form->handleRequest($request);

            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }

            $editDoctor = $form->getData();
            $this->userService->editDoctor($editDoctor, $doctorId);
        } catch (CreateDoctorException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(
            ['message' => sprintf('Dr. with id %d was edited correctly', $request->get('userId'))],
            Response::HTTP_OK
        );
    }

    /**
     * @throws \Exception
     */
    public function getDoctorAppointments(): Response
    {
        try {
            return $this->respond($this->userService->getDoctorAppointment($this->getUser()));
        } catch (CreateDoctorException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function editPersonalData(Request $request): Response
    {
        try {
            $form = $this->buildForm(EditPresonalDataForm::class);
            $form->handleRequest($request);

            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }

            /** @var User $editPersonalData */
            $editPersonalData = $form->getData();
            $this->userService->editPersonalData($editPersonalData, $this->getUser());
        } catch (CreateDoctorException $exception) {
            $this->loggerMyApi->error($exception);

            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(
            ['message' => 'Personal data was edited correctly'],
            Response::HTTP_OK
        );
    }
}
