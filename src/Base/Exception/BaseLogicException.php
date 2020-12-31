<?php

declare(strict_types=1);

namespace MagmaCore\Base\Exception;

use LogicException;

class BaseLogicException extends LogicException
{ 
    /**
     * Exception that represents error in the program logic. This kind of exception should
     * lead directly to a fix in your code.
     *
     * @param string $message
     * @param integer $code
     * @param LogicException $previous
     * @throws Exception
     */
    public function __construct(string $message, int $code = 0, LogicException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}