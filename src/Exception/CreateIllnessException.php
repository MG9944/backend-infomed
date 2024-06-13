<?php

namespace App\Exception;

use Exception;

class CreateIllnessException extends Exception
{
    public static function illnessNotFound(): Exception
    {
        throw new self('The illness does not exist');
    }

    public static function medicamenteNotFound(): Exception
    {
        throw new self('The medicamente given does not exist');
    }

    public static function illnessNotCreated(): Exception
    {
        throw new self('There was a problem when create an illness');
    }

    public static function illnessNotEdited(): Exception
    {
        throw new self('There was a problem when edit an illness');
    }
}
