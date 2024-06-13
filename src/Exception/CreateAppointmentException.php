<?php

namespace App\Exception;

use Exception;

class CreateAppointmentException extends Exception
{
    public static function appointmentNotFound(): Exception
    {
        throw new self('The appointment does not exist');
    }

    public static function patientNotFound(): Exception
    {
        throw new self('The patient given does not exist');
    }

    public static function appointmentNotCreated(): Exception
    {
        throw new self('There was a problem when create an appointment');
    }

    public static function appointmentNotEdited(): Exception
    {
        throw new self('There was a problem when edit an appointment');
    }
}
