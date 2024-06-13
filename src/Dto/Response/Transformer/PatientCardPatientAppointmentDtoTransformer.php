<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\PatientCardPatientAppointmentDto;
use App\Entity\Appointment;

class PatientCardPatientAppointmentDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * @param Appointment $appointment
     */
    public function transformFromObject($appointment): PatientCardPatientAppointmentDto
    {
        return new PatientCardPatientAppointmentDto(
            $appointment->getId(),
            $appointment->getAppointmentDate(),
            $appointment->getDiagnosis(),
            $appointment->getTemperature(),
            $appointment->getBloodPressure(),
            $appointment->getSugarLevel(),
            $appointment->getMedicamenteDescription(),
            $appointment->getDescription()
        );
    }
}
