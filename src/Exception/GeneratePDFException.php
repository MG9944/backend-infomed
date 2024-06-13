<?php

namespace App\Exception;

use Exception;

class GeneratePDFException extends Exception
{
    public static function appontmentNotFound(): Exception
    {
        throw new self('The appointment given does not exist');
    }

    public static function problemWithGeneration(): Exception
    {
        throw new self('There was a problem generating a file');
    }
}
