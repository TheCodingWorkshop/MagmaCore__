<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace MagmaCore\ErrorHandler;

use MagmaCore\Base\BaseView;
use MagmaCore\Utility\Yaml;
use ErrorException;

class ErrorHandler
{

    public function __construct()
    { }

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
        $view = new BaseView();
        if (self::isMode()['mode'] === 'dev' && self::isMode()['mode'] !=='prod') {
            echo $view->getErrorResource("/dev.html.twig", 
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

            echo $view->getErrorResource("/{$code}.html.twig", ["error_message" => $message]);
        }
    }


}
