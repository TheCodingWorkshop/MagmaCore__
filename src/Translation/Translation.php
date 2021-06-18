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

namespace MagmaCore\Translation;

use Exception;
use MagmaCore\Session\Exception\SessionException;
use MagmaCore\Session\GlobalManager\GlobalManagerException;
use MagmaCore\Utility\Yaml;
use MagmaCore\Session\SessionTrait;

class Translation implements TranslationInterface
{

    use SessionTrait;

    private static ?Translation $instance = null;

    /** @var array - Hold the translation strings */
    private array $message;

    public static function getInstance(): ?Translation
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @throws SessionException
     * @throws GlobalManagerException
     * @throws Exception
     */
    public function __construct()
    {
        $defaultLocale = Yaml::file('app')['settings']['default_locale'];
        $translation = $this->localeParser() ? $this->localeParser() : $defaultLocale;
        $localePath = APP_ROOT . "/Resources/translations/php";
        $locale = "{$localePath}/{$translation}/{$translation}.yml";
        $localeDefault = "{$localePath}/{$defaultLocale}/{$defaultLocale}.yml";
        var_dump(Yaml::file($localeDefault));

        if (
            file_exists($locale) && 
            is_readable($locale)) {
                return Yaml::file($locale);
        } else {
            return Yaml::file($localeDefault);
        }
        SessionTrait::sessionFromGlobal()->set('session_locale', $defaultLocale);
        $this->message = $message;
    }

    public function __get($name)
    {
        if (array_key_exists($name, (array)$this->message)) {
            return $this->message[$name];
        } else {
            return null;
        }
    }

    public function localeParser(): string
    {
        return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }

}
