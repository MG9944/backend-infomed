<?php

namespace App\Service;

use App\Dto\Response\Transformer\PatientCardPatientAppointmentDtoTransformer;
use App\Dto\Response\Transformer\PatientCardPatientInfoDtoTransformer;
use App\Entity\User;
use App\Exception\CreatePatientException;
use App\Repository\AppointmentRepository;
use App\Repository\PatientRepository;

class PatientCardService
{
    public function __construct(
        private readonly PatientCardPatientInfoDtoTransformer $cardPatientInfoDtoTransformer,
        private readonly PatientCardPatientAppointmentDtoTransformer $patientCardPatientAppointmentDtoTransformer,
        private readonly AppointmentRepository $appointmentRepository,
        private readonly PatientRepository $patientRepository,
    ) {
    }

    public function getPatientInfoInPatientCard(User $user, ?array $sort): iterable
    {
        $patients = $this->patientRepository->findAllByPatientsInMedicalCenterPatientCard($user, $sort);

        return $this->cardPatientInfoDtoTransformer->transformFromObjects($patients);
    }

    public function getPatientAppointmentsInPatientCard($patientId): iterable
    {
        try {
            $patient = $this->appointmentRepository->findBy(['idPatient' => $patientId, 'status' => AppointmentRepository::APPOINTMENT_TAKEN]);
        } catch (\Exception $exception) {
            throw CreatePatientException::patientNotFound();
        }

        return $this->patientCardPatientAppointmentDtoTransformer->transformFromObjects($patient);
    }
}
