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

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml as SymfonyYaml;
use Exception;

class Yaml
{

    /**
     * Check whether the specified yaml configuration file exists within
     * the specified directory else throw an exception
     *
     * @param string $filename
     * @return boolean
     * @throws Exception
     */
    private function isFileExists(string $filename)
    {
        if (!file_exists($filename))
            throw new \Exception($filename . ' does not exists');
    }

    /**
     * Load a yaml configuration
     *
     * @param string $yamlFile
     * @return void
     * @throws ParseException
     */
    public function getYaml(string $yamlFile)
    {
        foreach (glob(CONFIG_PATH . DIRECTORY_SEPARATOR . '*.yaml') as $file) {
            $this->isFileExists($file);
            $parts = parse_url($file);
            $path = $parts['path'];
            if (strpos($path, $yamlFile) !== false) {
                return SymfonyYaml::parseFile($file);
            }
        }
    }

    /**
     * Load a yaml configuration into the yaml parser
     *
     * @param string $yamlFile
     * @return void
     */
    public static function file(string $yamlFile) : array
    {
        return (array)(new Yaml())->getYaml($yamlFile);
    }

}
