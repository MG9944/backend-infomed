<?php

namespace App\Exception;

use Exception;

class CreateDoctorException extends Exception
{
    public static function emailAlreadyExist(): Exception
    {
        throw new self('A user with such an email already exists');
    }

    public static function specialisationNotFound(): Exception
    {
        throw new self('The specialization given does not exist');
    }

    public static function appointmentNotFound(): Exception
    {
        throw new self('The appointment does not found');
    }

    public static function medicalCenterNotFound(): Exception
    {
        throw new self('The medical center given does not exist');
    }

    public static function doctorNotFound(): Exception
    {
        throw new self('The email does not exist');
    }
}
