<?php

namespace App\Exception;

use Exception;

class CreatePatientException extends Exception
{
    public static function medicalCenterNotFound(): Exception
    {
        throw new self('The medical center given does not exist');
    }

    public static function patientNotCreated(): Exception
    {
        throw new self('There was a problem when creating a patient account');
    }

    public static function patientNotFound(): Exception
    {
        throw new self('The patient does not exist');
    }

    public static function patientNotEdited(): Exception
    {
        throw new self('There was a problem when edit a patient account');
    }
}
