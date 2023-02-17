<?php

namespace App\Exceptions;

use Exception;

class ForbiddenException extends Exception
{
    public function __construct($message = "", $code = 0, \Throwable$previous = null)
    {
        $this->code    = 403;
        $this->message = $message;
    }

    public function render($request)
    {
    }
}
