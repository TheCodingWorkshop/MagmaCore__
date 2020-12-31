<?php

declare(strict_types=1);

namespace MagmaCore\Base\Exception;

use RuntimeException;

class BaseRuntimeException  extends RuntimeException  
{ 

    /**
     * Exception thrown if an error which can only be found on runtime occurs.
     *
     * @param string $message
     * @param integer $code
     * @param RuntimeException $previous
     * @throws Exception
     */
    public function __construct(string $message, int $code = 0, RuntimeException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}