<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\AppointmentDto;
use App\Entity\Appointment;

class AppointmentDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * @param Appointment $appointment
     */
    public function transformFromObject($appointment): AppointmentDto
    {
        return new AppointmentDto(
            $appointment->getId(),
            $appointment->getAppointmentDate(),
            $appointment->getUser()->getFullName(),
            $appointment->getIdPatient()->getFullName(),
            $appointment->getDiagnosis()
        );
    }
}
