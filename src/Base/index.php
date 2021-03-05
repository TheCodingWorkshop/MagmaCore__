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

/**
 * Load main application directory path constant
 */
defined('ROOT_PATH') or define('ROOT_PATH', realpath(dirname(dirname(__FILE__))));
defined('ROOT_URI') or define('ROOT_URI', '');
defined('RESOURCES') or define('RESOURCES', ROOT_URI);
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('UPLOAD_PATH') or define("UPLOAD_PATH", $_SERVER['DOCUMENT_ROOT'] . DS . "uploads/");
defined('CONFIG_PATH') or define("CONFIG_PATH", ROOT_PATH . DS . "Config/");

/**
 * PHP 5.4 ships with a built in web server for development. This server
 * allows us to run silex without any configuration. However in order
 * to server static files we need to handle it nicely
 */
$filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() == 'cli-server' && is_file($filename)) {
    return false;
}

/**
 * Requires composer autoload. Which loads external libraries and MagmaCore libraries
 */
$autoload = ROOT_PATH . '/vendor/autoload.php';
if (is_file($autoload)) {
    require $autoload;
}

/**
 * Load BaseApplication class. Which ignites and ties the MagmaCore framework
 * together.
 */

use MagmaCore\Base\BaseApplication;
use MagmaCore\Utility\Yaml;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\ErrorHandler\DebugClassLoader;
Debug::enable();

(new BaseApplication())
    ->setPath(ROOT_PATH)
        ->setConfig(Yaml::file('app'))
            ->setSession(Yaml::file('session'))
                ->setCookie([])
                    ->setCache([])
                        ->setRoutes(Yaml::file('routes'))
                            ->setContainerProviders([])
                                ->run();

