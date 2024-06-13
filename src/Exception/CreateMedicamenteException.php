<?php

namespace App\Exception;

use Exception;

class CreateMedicamenteException extends Exception
{
    public static function problemWithConnectToExternalAPI(): Exception
    {
        throw new self('There was a problem with connect to external API');
    }

    public static function medicamenteNotCreated(): Exception
    {
        throw new self('There was a problem when creating a medicamente');
    }

    public static function medicamenteNotFound(): Exception
    {
        throw new self('The medicamente does not exist');
    }

    public static function medicamenteNotEdited(): Exception
    {
        throw new self('There was a problem when edit a medicamente');
    }
}
