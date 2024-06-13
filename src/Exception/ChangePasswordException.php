<?php

namespace App\Exception;

use Exception;

class ChangePasswordException extends Exception
{
    public static function oldPasswordNotCorrect(): Exception
    {
        throw new self('Old password is incorrect');
    }

    public static function newPasswordNotCorrect(): Exception
    {
        throw new self('New passwords do not match');
    }

    public static function passwordNotCorrect(): Exception
    {
        throw new self('The new password cannot be the same as the old one');
    }

    public static function forgotPasswordNotAvailable(): Exception
    {
        throw new self('The forgot password is not available');
    }

    public static function verificationNotCorrect(): Exception
    {
        throw new self('Verification code is incorrect');
    }
}
