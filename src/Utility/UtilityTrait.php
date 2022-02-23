<?php

namespace MagmaCore\Utility;

use MagmaCore\Utility\Yaml;

trait UtilityTrait
{
    

    public function security(string $key): mixed
    {
        return Yaml::file('app')['security'][$key];
    }

    public static function appSecurity(string $key): mixed
    {
        return Yaml::file('app')['security'][$key];
    }

    /**
     * Returns true if the argument is an array and it has atleast 1 element else return false
     * @param array $array
     * @param int|null $count - defaults to 0. This indicate there as to be atleast 1 element
     * @return bool
     */
    public function isArrayCountable(array $array = [], ?int $count = 0): bool
    {
        return is_array($array) && count($array) > 0 ? true : false;
    }

    /**
     * @param string|null $dir
     * @return array
     */
    function dirToArray(string $dir = null): array
    {
        $result = array();
        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value,array(".",".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }


}