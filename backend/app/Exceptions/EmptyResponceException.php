<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class EmptyResponceException extends Exception
{
    public function __construct($message = 'Контрагент не найден', $code = Response::HTTP_NOT_FOUND)
    {
        parent::__construct($message, $code);
    }
}
