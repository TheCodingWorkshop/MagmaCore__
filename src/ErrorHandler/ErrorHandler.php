<?php

declare(strict_types=1);

namespace MagmaCore\ErrorHandler;

use MagmaCore\Base\BaseView;
use MagmaCore\Utility\Yaml;
use ErrorException;

class ErrorHandler
{

    /**
     * Error Handler. Convert all errors to exception by throwing an 
     * ErrorException
     *
     * @return void
     */
    public static function errorHandle($severity, $message, $file, $line)
    {
        if (!error_reporting() !== 0) {
            return;
        }
        throw new ErrorException($message, 0, $file, $line);
    }

    public static function isMode()
    {
        //$error = false;
        $mode = Yaml::file('app')['debug_error'];
        return $mode;
    }

    /**
     * Exception handler.
     *
     * @param Exception $exception The exception
     * @return void
     * @throws Exception
     */
    public static function exceptionHandle($exception)
    {
        $code = $exception->getCode();
        if ($code !=404) {
            $code = 500;
        }
        http_response_code($code);
        
        if (self::isMode()['mode'] === 'dev' && self::isMode()['mode'] !=='prod') {
            echo (new BaseView)->getErrorResource("/dev.html.twig", 
                [
                    "exception" => $exception,
                    "exception_class" => get_class($exception)
                ]
            );
        } else {
            $errorLog = LOG_PATH . "/" . date("Y-m-d H:is") . ".txt";
            ini_set('error_log', $errorLog);    
            $message = "Uncaught exception: " . get_class($exception);
            $message .= "with message " . $exception->getMessage();
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in " . $exception->getFile() . " on line " . $exception->getLine();
            error_log($message);

            echo (new BaseView)->getErrorResource("/{$code}.html.twig", ["error_message" => $message]);
        }
    }


}
