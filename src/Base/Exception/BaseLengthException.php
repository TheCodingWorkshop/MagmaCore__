<?php

declare(strict_types=1);

namespace MagmaCore\Base\Exception;

use LengthException;

class BaseLengthException   extends LengthException  
{ 
    /**
     * Exception thrown if a length is invalid. 
     *
     * @param string $message
     * @param integer $code
     * @param LengthException  $previous
     * @throws LogicException
     */
    public function __construct(string $message, int $code = 0, LengthException   $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}