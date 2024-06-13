<?php

namespace App\Factory;

use App\Controller\AbstractApiController;
use App\Entity\Patient;
use App\Entity\User;
use App\Exception\CreatePatientException;
use App\Security\DataChecker;
use Doctrine\Persistence\ManagerRegistry;

class PatientFactory extends AbstractApiController
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly DataChecker $dataChecker
    ) {
    }

    /**
     * @throws CreatePatientException
     * @throws \Exception
     */
    public function createPatient(Patient $patientCreate, User $doctor)
    {
        $patient = new Patient();
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
            $manager = $this->doctrine->getManager();
            $manager->persist($patient);
            $manager->flush();
        } catch (\Exception $exception) {
            throw CreatePatientException::patientNotCreated();
        }
    }
}
