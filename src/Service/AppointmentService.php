<?php

namespace App\Service;

use App\Controller\AbstractApiController;
use App\Dto\Response\Transformer\AppointmentDtoTransformer;
use App\Entity\Appointment;
use App\Entity\User;
use App\Exception\CreateAppointmentException;
use App\Repository\AppointmentRepository;
use App\Repository\PatientRepository;
use Doctrine\Persistence\ManagerRegistry;

class AppointmentService extends AbstractApiController
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly PatientRepository $patientRepository,
        private readonly AppointmentRepository $appointmentRepository,
        private readonly AppointmentDtoTransformer $appointmentDtoTransformer,
        private readonly SMSService $SMSService
    ) {
    }

    public function getAppointments(User $doctor): iterable
    {
        $appointments = $this->appointmentRepository->findBy(['user' => $doctor->getId(), 'status' => [AppointmentRepository::APPOINTMENT_NEW, AppointmentRepository::APPOINTMENT_EDIT]]);

        return $this->appointmentDtoTransformer->transformFromObjects($appointments);
    }

    /**
     * @throws \Exception
     */
    public function editAppointment(array $appointmentCreate, $appointmentId, User $doctor)
    {
        try {
            if (!$appointment = $this->appointmentRepository->find($appointmentId)) {
                throw CreateAppointmentException::appointmentNotFound();
            }
            $appointment->setAppointmentDate($appointmentCreate['appointmentDate']);
            $appointment->setDiagnosis($appointmentCreate['diagnose']);
            $appointment->setUser($doctor);;

            $patient = $this->patientRepository->findOneBy(['id' => $appointmentCreate['patient']]);

            if (empty($patient)) {
                throw CreateAppointmentException::patientNotFound();
            }
            $appointment->setIdPatient($patient);

            $appointment->setStatus(AppointmentRepository::APPOINTMENT_EDIT);
            $manager = $this->doctrine->getManager();
            $manager->persist($appointment);
            $manager->flush();
        } catch (\Exception $exception) {
            throw CreateAppointmentException::appointmentNotCreated();
        }
    }

    /**
     * @throws CreateAppointmentException
     */
    public function cancelAppointment($appointmentId): void
    {
        try {
            if (!$appointment = $this->appointmentRepository->find($appointmentId)) {
                throw CreateAppointmentException::appointmentNotFound();
            }

            $appointment->setStatus(AppointmentRepository::APPOINTMENT_CANCELED);
            $manager = $this->doctrine->getManager();
            $manager->persist($appointment);
            $manager->flush();
        } catch (\Exception $e) {
            throw CreateAppointmentException::appointmentNotEdited();
        }
    }

    public function continueInsertAppointmentData(Appointment $appointmentEdit, $appointmentId)
    {
        try {
            if (!$appointment = $this->appointmentRepository->find($appointmentId)) {
                throw CreateAppointmentException::appointmentNotFound();
            }

            if (AppointmentRepository::APPOINTMENT_CANCELED == $appointment->getStatus()) {
                throw CreateAppointmentException::appointmentNotFound();
            }

            $appointment->setTemperature($appointmentEdit->getTemperature());
            $appointment->setBloodPressure($appointmentEdit->getBloodPressure());
            $appointment->setSugarLevel($appointmentEdit->getSugarLevel());
            $appointment->setMedicamenteDescription($appointmentEdit->getMedicamenteDescription());
            $appointment->setDescription($appointmentEdit->getDescription());
            $appointment->setStatus(AppointmentRepository::APPOINTMENT_TAKEN);

            $manager = $this->doctrine->getManager();
            $manager->persist($appointment);
            $manager->flush();
        } catch (\Exception $e) {
            throw CreateAppointmentException::appointmentNotEdited();
        }
    }
}
