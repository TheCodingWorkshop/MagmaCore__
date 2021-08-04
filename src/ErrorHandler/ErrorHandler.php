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

use MagmaCore\Ash\TemplateEnvironment;
use MagmaCore\Base\BaseView;
use MagmaCore\Utility\Yaml;
use ErrorException;

class ErrorHandler
{

    /**
     * Number of lines to be returned
     */
    private const NUM_LINES = 10;
    private static $trace = [];

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function isMode()
    {
        $mode = Yaml::file('app')['debug_error'];
        return $mode;
    }

    /**
     * Error Handler. Convert all errors to exception by throwing an 
     * ErrorException
     *
     * @return void
     */
    public static function errorHandle($errno, $errstr, $errfile, $errline)
    {
        if (error_reporting() !==0) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
        register_shutdown_function(function(){
            $error = error_get_last();
            if($error){
                throw new ErrorException($error['message'], -1, $error['type'], $error['file'], $error['line']);
            }
        });

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
        self::buildStackTrace($exception);
        $code = $exception->getCode();
        if ($code !=404) {
            $code = 500;
        }
        http_response_code($code);

        if (self::isMode()['mode'] == 'dev' && self::isMode()['mode'] !='prod') {
            list($srcCode, $snippet) = self::srcCode($exception->getFile(), $exception->getLine(), 'highlight');
            $stacktrace = self::$trace;
            include 'Resources/Templates/dev.php';

        } else {

            $logFile = LOG_PATH . "/error-" . date('Y-m-d') . "-.log";
            ini_set("log_errors", 'On');
            ini_set('error_log', $logFile);

            $message = "Uncaught exception: " . get_class($exception);
            $message .= "with message " . $exception->getMessage();
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in " . $exception->getFile() . " on line " . $exception->getLine();
            error_log($message);
            include "Resources/Templates/{$code}.php";
        }
    }

    public static function srcCode($errfile, $errline)
    {
        $range = array($errline -5, $errline +10);
        $src = explode(PHP_EOL, file_get_contents($errfile));
        $snippet = $src[$errline -2] ?? null;
        for ($i = $range[0]; $i <= $range[1]; ++$i) {
            if (!isset($src[$i])) {
                return;
            }
            if ($i === count($src))
                break;
            if ($i === $errline -1) {
                $code = sprintf("%d | %s <<<<< Here is the error\n", $i, $src[$i]);
            } else {
                $code = sprintf("%d | %s", $i, $src[$i]);
            }
        }
        return [
            $code,
            $snippet
        ];

    }

    /**
     * Gets the content between given lines
     * @param string $filename
     * @param int $offset
     * @param int|null $length
     * @param int $flags
     * @return array
     */
    public static function getLines(string $filename, int $offset, ?int $length, int $flags = 0): array
    {
        return array_slice(file($filename, $flags), $offset, $length, true);
    }

    /**
     * @param $errfile
     * @param $errline
     * @param $errclass
     * @return string
     */
    public static function getSrcCode($errfile, $errline, $errclass)
    {
        $start = max($errline - floor(self::NUM_LINES / 2), 1);
        $start = (int)$start;
        $lines = self::getLines($errfile, $start, self::NUM_LINES, FILE_IGNORE_NEW_LINES);
        $code = '<ul class="uk-list uk-list-divider uk-list-collapse uk-text-bolder" start="' . key($lines) . '">';
        foreach ($lines as $currentLineNumbers => $line) {
            $code .= '<li' . ($currentLineNumbers == $errline -1 ? ' class="' . $errclass . '"' : '') . '>';
            $code .= $currentLineNumbers +1 . ' ' . $line;
            $code .= '</li>';
        }
        $code .= '</ul>';
        return $code;
    }

    public static function buildStackTrace($exception)
    {
        self::$trace[] = [
            'file' => $exception->getFile(),
            'code' => self::getSrcCode($exception->getFile(), $exception->getLine(), 'error-line')
        ];
        foreach ($exception->getTrace() as $item) {
            if (isset($item['class']) && $item['class'] == __CLASS__) {
                continue;
            }
            if (isset($item['file'])) {
                self::$trace[] = [
                    'file' => $item['file'],
                    'code' => self::getSrcCode($item['file'], $item['line'], 'switch-line')
                ];
            }
        }
    }

}
