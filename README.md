# MagmaCore__
This project is in active development. So is evolving constantly. As soon project gets to stable point. Due notice will be given

## Composer Check
Note MagmaCoreStarter requires the MagmaCore__ framework as its dependency. Ensure all dependency  is present within your composer.json file.

```console
composer require magmacore/magmacore
composer require phpunit/phpunit
composer require phpmailer/phpmailer
composer require symfony/console
```

## Env Check
By default the MagmaCoreStarter package comes with a .envExample file. You should rename this to .env and ensure the following fields are filled out.

```console
DB_DRIVER=mysql - the database default driver - This is only mysql for the time being
DB_HOST=127.0.0.1 - the address for your webserver. Provided by your web host provider
DB_NAME=lavacms - the name of your database
DB_USER=root - the secure username for your database
DB_PASSWORD= - By default local development doesn't require a password LIVE SERVERS DOES!!!
DB_PREFIX=__magmaCore__ - a prefix which gets append to your database table names
DB_PORT=3306 - defaults database port
```

## Installation
The main package can be installed using composer from your terminal.

```console
composer require magmacore/magmacore-starter
```

## Getting Started
There's 2 .htaccess file the first one located within the main root directory. Which takes the incoming request which routes this to the **public/index.php file. This index.php file handles the initialization of the application. This file is also used to load all the main component within the framework i.e session, cache, routing, error-handling etc...

Your basic index file should look like below.

```console
require_once 'include.php';

use MagmaCore\Utility\Yaml;
use MagmaCore\Logger\LogLevel;
use MagmaCore\Base\BaseApplication;

try {
    BaseApplication::getInstance()
        ->setPath(ROOT_PATH)
        ->setConfig(Yaml::file('app'))
        ->setErrorHandler(Yaml::file('app')['error_handler'], E_ALL)
        ->setSession(Yaml::file('app')['session'], null, true)
        ->setCookie([])
        ->setCache(Yaml::file('app')['cache'], null, true)
        ->setRoutes(Yaml::file('routes'))
        ->setLogger(LOG_PATH, Yaml::file('app')['logger_handler']['file'], LogLevel::DEBUG, [])
        ->setContainerProviders(Yaml::file('providers'))
        ->run();
} catch (Exception $e) {
    echo $e->getMessage();
}

```
