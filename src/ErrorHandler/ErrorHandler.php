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
    /*public static function errorHandle($severity, $message, $file, $line)
    {
        if (!error_reporting() !== 0) {
            return;
        }
        throw new ErrorException($message, 0, $severity, $file, $line);
    }*/
    public static function errorHandle($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return false;
        }
    
        // $errstr may need to be escaped:
        $errstr = htmlspecialchars($errstr);
    
        switch ($errno) {
        case E_USER_ERROR:
            echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
            echo "  Fatal error on line $errline in file $errfile";
            echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            echo "Aborting...<br />\n";
            exit(1);
    
        case E_USER_WARNING:
            echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
            break;
    
        case E_USER_NOTICE:
            echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
            break;
    
        default:
            echo "Unknown error type: [$errno] $errstr<br />\n";
            break;
        }
    
        
        /* Don't execute PHP internal error handler */
        //return true;
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
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
  
            $logFile = LOG_PATH . "/error-" . date('Y-m-d') . "-.log"; 
            ini_set("log_errors", 'On');  
            ini_set('error_log', $logFile); 
  
            $message = "Uncaught exception: " . get_class($exception);
            $message .= "with message " . $exception->getMessage();
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in " . $exception->getFile() . " on line " . $exception->getLine();
            error_log($message);
            echo $view->getErrorResource("/{$code}.html.twig", ["error_message" => $message]);
        }
    }


}
