<?php

namespace App\Exception;

use Exception;

class AppointmentSMSReminderException extends Exception
{
    public static function problemWithSendSMSReminder(): Exception
    {
        throw new self('There was a problem with send SMS');
    }
}
