<?php

declare(strict_types=1);

namespace MagmaCore\Datatable\Exception;

use UnexpectedValueException;

class DatatableUnexpectedValueException  extends UnexpectedValueException 
{ 
    /**
     * Exception thrown if a value does not match with a set of values. Typically this happens 
     * when a function calls another function and expects the return value to be of a certain 
     * type or value not including arithmetic or buffer related errors. 
     * 
     * @param string $message
     * @param integer $code
     * @param UnexpectedValueException $previous
     * @throws RuntimeException
     */
    public function __construct(string $message, int $code = 0, UnexpectedValueException  $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}