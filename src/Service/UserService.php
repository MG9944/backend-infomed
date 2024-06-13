<?php

namespace App\Service;

use App\Dto\Response\Transformer\AppointmentDtoTransformer;
use App\Dto\Response\Transformer\UserDtoTransformer;
use App\Entity\User;
use App\Exception\ChangeDoctorAccountStatus;
use App\Exception\CreateDoctorException;
use App\Exception\EditDoctorAccountException;
use App\Repository\AppointmentRepository;
use App\Repository\MedicalCenterRepository;
use App\Repository\SpecialisationRepository;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(
        private readonly UserDtoTransformer $userDtoTransformer,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly MedicalCenterRepository $medicalCenterRepository,
        private readonly SpecialisationRepository $specialisationRepository,
        private readonly AppointmentRepository $appointmentRepository,
        private readonly AppointmentDtoTransformer $appointmentDtoTransformer,
    ) {
    }

    public function getDoctorsByMedicalCenter(User $user, ?array $sort): iterable
    {
        $patients = $this->userRepository->findAllByMedicalCenter($user, $sort);

        return $this->userDtoTransformer->transformFromObjects($patients);
    }

    /**
     * @throws ChangeDoctorAccountStatus
     */
    public function changeDoctorStatus(string $userId)
    {
        try {
            if (!$user = $this->userRepository->find($userId)) {
                throw ChangeDoctorAccountStatus::doctorNotFound();
            }

            $user->setIsActive(!$user->isIsActive());
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw ChangeDoctorAccountStatus::doctorNotEdited();
        }
    }

    public function editDoctor(array $editUser, $userId)
    {
        try {
            if (!$user = $this->userRepository->find($userId)) {
                throw EditDoctorAccountException::doctorNotFound();
            }
            $user->setEmail($editUser['email']);
            $user->setFirstName($editUser['firstName']);
            $user->setLastName($editUser['lastName']);
            $user->setPhoneNumber($editUser['phoneNumber']);
            $specialisation = $this->specialisationRepository->findOneBy(['id' => $editUser['specialisation']]);

            if (null == $specialisation) {
                throw CreateDoctorException::specialisationNotFound();
            }

            $user->setSpecialisation($specialisation);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception  $exception) {
            throw EditDoctorAccountException::doctorNotEdited();
        }
    }

    public function getDoctorAppointment(User $user): iterable
    {
        try {
            $date = Carbon::now();
            $currentUser = $this->appointmentRepository->getAllByDoctorAndDate($user, $date);
        } catch (\Exception $e) {
            throw CreateDoctorException::appointmentNotFound();
        }

        return $this->appointmentDtoTransformer->transformFromObjects($currentUser);
    }

    public function editPersonalData(User $user, User $userId): void
    {
        try {
            if (!$editedUser = $this->userRepository->find($userId->getId())) {
                throw EditDoctorAccountException::doctorNotFound();
            }
            $editedUser->setEmail($user->getEmail());
            $editedUser->setPhoneNumber($user->getPhoneNumber());

            $this->entityManager->persist($editedUser);
            $this->entityManager->flush();
        } catch (\Exception  $exception) {
            throw EditDoctorAccountException::doctorNotEdited();
        }
    }
}
