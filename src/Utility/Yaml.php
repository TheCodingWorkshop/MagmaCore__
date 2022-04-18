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
use Throwable;
use Exception;

class Yaml
{
    /* @var array returns a list of protected config filename application config must not use. */
    private const PROTECTED_CONFIG_FILE_NAMES = [
        'events',
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

    private function getAppConfigs(): array
    {
        if (defined('CONFIG_PATH')) {
            $appConfig = glob(CONFIG_PATH . DIRECTORY_SEPARATOR . '*.yml');
            if (count($appConfig)) {
                return $appConfig;
            }
        }
    }

    public function getYaml(string $ymlFile)
    {
        if (defined('CORE_CONFIG_PATH')) {
            $coreConfig = glob(CORE_CONFIG_PATH . DIRECTORY_SEPARATOR . '*.yml');
            $appConfig = $this->getAppConfigs();
            $this->throwExceptionIfNameCollision($appConfig);
            try {
                $combineDirs = array_merge($coreConfig, $appConfig);
                foreach ($combineDirs as $file) {
                    $this->isFileExists($file);
                    if (str_contains($file, $ymlFile)) {
                        return SymfonyYaml::parseFile($file);
                    }
                    
                }
            }catch(Throwable $throw) {

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
