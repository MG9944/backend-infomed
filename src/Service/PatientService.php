<?php

namespace App\Service;

use App\Dto\Response\Transformer\PatientDtoTransformer;
use App\Entity\Patient;
use App\Entity\User;
use App\Exception\CreatePatientException;
use App\Repository\PatientRepository;
use App\Security\DataChecker;
use Doctrine\ORM\EntityManagerInterface;

class PatientService
{
    public function __construct(
        private readonly PatientDtoTransformer $patientDtoTransformer,
        private readonly PatientRepository $patientRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly DataChecker $dataChecker
    ) {
    }

    public function getPatientsByMedicalCenter(User $user, ?array $sort = []): iterable
    {
        $patients = $this->patientRepository->findAllByMedicalCenter($user, $sort);

        return $this->patientDtoTransformer->transformFromObjects($patients);
    }

    /**
     * @throws CreatePatientException
     * @throws \Exception
     */
    public function editPatient(Patient $patientCreate, $patientId, User $doctor)
    {
        if (!$patient = $this->patientRepository->find($patientId)) {
            throw CreatePatientException::patientNotFound();
        }
        $patient->setPesel($this->dataChecker->encrypt($patientCreate->getPesel()));
        $patient->setFirstName($patientCreate->getFirstName());
        $patient->setLastName($patientCreate->getLastName());
        $patient->setAddress($patientCreate->getAddress());
        $patient->setPostCode($patientCreate->getPostCode());
        $patient->setCity($patientCreate->getCity());
        $patient->setPhoneNumber($patientCreate->getPhoneNumber());
        $medicalCenter = $doctor->getMedicalCenter();
        if (empty($medicalCenter)) {
            throw CreatePatientException::medicalCenterNotFound();
        }
        $patient->setMedicalCenter($medicalCenter);
        try {
            $this->entityManager->persist($patient);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw CreatePatientException::patientNotEdited();
        }
    }
}
