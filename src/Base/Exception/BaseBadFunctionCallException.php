<?php

declare(strict_types=1);

namespace MagmaCore\Base\Exception;

use BadFunctionCallException;

class BaseBadFunctionCallException  extends BadFunctionCallException 
{ 
    /**
     * Exception thrown if a callback refers to an undefined function or if some arguments are missing. 
     *
     * @param string $message
     * @param integer $code
     * @param BadFunctionCallException $previous
     * @throws LogicException
     */
    public function __construct(string $message, int $code = 0, BadFunctionCallException  $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}