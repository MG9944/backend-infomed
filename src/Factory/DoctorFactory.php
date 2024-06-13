<?php

namespace App\Factory;

use App\Controller\AbstractApiController;
use App\Entity\User;
use App\Exception\CreateDoctorException;
use App\Repository\MedicalCenterRepository;
use App\Repository\SpecialisationRepository;
use App\Service\EmailService;
use App\Service\SMSService;
use App\Utils\GenerateRandomCode;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DoctorFactory extends AbstractApiController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ManagerRegistry $doctrine,
        private readonly MedicalCenterRepository $medicalCenterRepository,
        private readonly SpecialisationRepository $specialisationRepository,
        private readonly SMSService $SMSService,
        private readonly EmailService $emailService,
        private readonly GenerateRandomCode $generateRandomCode
    ) {
    }

    /**
     * @throws CreateDoctorException
     */
    public function createDoctor(array $data)
    {
        $doctor = new User();
        $doctor->setEmail($data['email']);
        $doctor->setFirstName($data['firstName']);
        $doctor->setLastName($data['lastName']);
        $doctor->setIsActive(true);
        $doctor->setRoles(['ROLE_DOCTOR']);
        $doctor->setPhoneNumber($data['phoneNumber']);
        $doctor->setPassword($this->passwordHasher->hashPassword($doctor, $data['password']));
        $specialisation = $this->specialisationRepository->findOneBy(['id' => $data['specialisation']]);
        $medicalCenter = $this->medicalCenterRepository->findOneBy(['id' => $data['medicalCenter']]);
        if (null == $medicalCenter) {
            throw CreateDoctorException::medicalCenterNotFound();
        }

        if (null == $specialisation) {
            throw CreateDoctorException::specialisationNotFound();
        }

        $doctor->setMedicalCenter($medicalCenter);
        $doctor->setSpecialisation($specialisation);
        try {
            $manager = $this->doctrine->getManager();
            $manager->persist($doctor);
            $manager->flush();
        } catch (\Exception $exception) {
            throw CreateDoctorException::emailAlreadyExist();
        }
    }

    /**
     * @throws CreateDoctorException
     */
    public function createNewDoctor(array $data, User $registredUser)
    {
        $doctor = new User();
        $doctor->setEmail($data['email']);
        $doctor->setFirstName($data['firstName']);
        $doctor->setLastName($data['lastName']);
        $doctor->setIsActive(true);
        $doctor->setRoles(['ROLE_DOCTOR']);
        $doctor->setPhoneNumber($data['phoneNumber']);
        $password = $this->generateRandomCode->generateRandomString(12);
        $doctor->setPassword($this->passwordHasher->hashPassword($doctor, $password));
        $specialisation = $this->specialisationRepository->findOneBy(['id' => $data['specialisation']]);

        $medicalCenter = $registredUser->getMedicalCenter();
        if (empty($medicalCenter)) {
            throw CreateDoctorException::medicalCenterNotFound();
        }

        if (null == $specialisation) {
            throw CreateDoctorException::specialisationNotFound();
        }

        $doctor->setMedicalCenter($medicalCenter);
        $doctor->setSpecialisation($specialisation);
        try {
            $manager = $this->doctrine->getManager();
            $manager->persist($doctor);
            $manager->flush();
            $this->emailService->sendEmailNewUser($doctor->getEmail(), $password);
            $this->SMSService->sendSMSNewUserRegistred($doctor->getPhoneNumber(), $doctor->getEmail(), $password);
        } catch (\Exception $exception) {

        }
    }
}
