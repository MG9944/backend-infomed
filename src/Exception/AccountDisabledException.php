<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountDisabledException extends AccountStatusException
{
    public static function problemAccount(): AccountStatusException
    {
        throw new self('Your account has been deactivated and you cannot access it');
    }
}
