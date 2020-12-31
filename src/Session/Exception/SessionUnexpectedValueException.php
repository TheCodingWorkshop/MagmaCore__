<?php

declare(strict_types=1);

namespace MagmaCore\Session\Exception;

use UnexpectedValueException;

class SessionUnexpectedValueException extends UnexpectedValueException
{ 

    public function __construct(
        string $message = null, 
        int $code = 0, 
        UnexpectedValueException $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}