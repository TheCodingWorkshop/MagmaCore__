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

## Composer Check
Note MagmaCoreStarter requires the MagmaCore__ framework as its dependency. Ensure all dependency  is present within your composer.json file.

```console
composer require magmacore/magmacore
composer require phpunit/phpunit
composer require phpmailer/phpmailer
composer require symfony/console
```
