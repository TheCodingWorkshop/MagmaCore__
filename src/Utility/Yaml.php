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

namespace MagmaCore\Utility;

use MagmaCore\Base\Exception\BaseRuntimeException;
use MagmaCore\Base\Exception\BaseUnexpectedValueException;
use Symfony\Component\VarDumper\Exception\ThrowingCasterException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml as SymfonyYaml;
use Exception;

class Yaml
{
    /* @var array returns a list of protected config filename application config must not use. */
    private const PROTECTED_CONFIG_FILE_NAMES = [
        'event',
    ];

    /**
     * Check whether the specified yaml configuration file exists within
     * the specified directory else throw an exception
     *
     * @param string $filename
     * @return void
     * @throws Exception
     */
    private function isFileExists(string $filename)
    {
        if (!file_exists($filename))
            throw new Exception($filename . ' does not exists');
    }

    /**
     * Load a yaml configuration from either the core config directory or the application
     * config directory
     *
     * @param string $yamlFile
     * @return void
     * @throws ParseException|Exception
     */
    public function getYaml(string $yamlFile)
    {
        if (defined('CONFIG_PATH') && defined('CORE_CONFIG_PATH')) {
            $coreConfigDir = glob(CORE_CONFIG_PATH . DIRECTORY_SEPARATOR . '*.yml');
            $appConfigDir = glob(CONFIG_PATH . DIRECTORY_SEPARATOR . '*.yml');
            /* Prevent name collision by throwing an exception */
            $this->throwExceptionIfNameCollision($appConfigDir);

            try {
                $mergeDir = array_merge($coreConfigDir, $appConfigDir);
                foreach ($mergeDir as $file) {
                    $this->isFileExists($file);
                    $parts = parse_url($file);
                    $path = $parts['path'];
                    if (str_contains($path, $yamlFile)) {
                        return SymfonyYaml::parseFile($file);
                    }
                }
            } catch(\Throwable $throw) {
            }

        }
    }

    /**
     * Prevent name collision if a user create an application config file which is already
     * being used by the core config. The core config will take the lead in the situation.
     * This will throw an exception telling the user to rename their config file
     *
     * @param array $appConfigDir
     * @throws BaseRuntimeException
     * @return void
     */
    private function throwExceptionIfNameCollision(array $appConfigDir): void
    {
        if (is_array($appConfigDir) && count($appConfigDir) > 0) {
            foreach ($appConfigDir as $filename) {
                /* Lets the explode the string by a delimiter */
               if (str_contains($filename, '\\')) {
                   $string = explode('\\', $filename);
                   $element = array_pop($string);
                   if (str_contains($element, '.yml')) {
                       $el = explode('.yml', $element);
                       $final = array_shift($el);
                       if (in_array($final, self::PROTECTED_CONFIG_FILE_NAMES)) {
                           throw new BaseRuntimeException("[{$final}] name is a protected core config filename. Please give your config file a different name.");
                       }
                   }
               }
            }
        }
    }

    /**
     * Load a yaml configuration into the yaml parser
     *
     * @param string $yamlFile
     * @return array
     * @throws Exception
     */
    public static function file(string $yamlFile) : array
    {
        return (array)(new Yaml())->getYaml($yamlFile);
    }


}
