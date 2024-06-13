<?php

namespace App\Exception;

use Exception;

class ChangeDoctorAccountStatus extends Exception
{
    public static function doctorNotFound(): Exception
    {
        throw new self('The doctor given does not exist');
    }

    public static function doctorNotEdited(): Exception
    {
        throw new self('There was a problem when edit a doctor');
    }
}
