<?php

namespace App\Factory;

use App\Controller\AbstractApiController;
use App\Entity\Appointment;
use App\Entity\User;
use App\Exception\CreateAppointmentException;
use App\Repository\AppointmentRepository;
use App\Repository\PatientRepository;
use Doctrine\Persistence\ManagerRegistry;

class AppointmentFactory extends AbstractApiController
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly PatientRepository $patientRepository,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function createAppointment(array $appointmentCreate, User $doctor)
    {
        $appointment = new Appointment();
        $appointment->setAppointmentDate($appointmentCreate['appointmentDate']);
        $appointment->setDiagnosis($appointmentCreate['diagnose']);
        $appointment->setUser($doctor);

        $patient = $this->patientRepository->findOneBy(['id' => $appointmentCreate['patient']]);

        if (empty($patient)) {
            throw CreateAppointmentException::patientNotFound();
        }
        $appointment->setIdPatient($patient);
        $appointment->setStatus(AppointmentRepository::APPOINTMENT_NEW);
        try {
            $manager = $this->doctrine->getManager();
            $manager->persist($appointment);
            $manager->flush();
        } catch (\Exception $exception) {
            throw CreateAppointmentException::appointmentNotCreated();
        }
    }
}
