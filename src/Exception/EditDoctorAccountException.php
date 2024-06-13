<?php

namespace App\Exception;

use Exception;

class EditDoctorAccountException extends Exception
{
    public static function doctorNotFound(): Exception
    {
        throw new self('The doctor given does not exist');
    }

    public static function doctorNotEdited(): Exception
    {
        throw new self('There was a problem when edit a doctor');
    }

    public static function medicalCenterNotFound(): Exception
    {
        throw new self('The medical center given does not exist');
    }

    public static function specialisationNotFound(): Exception
    {
        throw new self('The specialization given does not exist');
    }

    public static function roleNotFound(): Exception
    {
        throw new self('The role given does not exist');
    }
}
