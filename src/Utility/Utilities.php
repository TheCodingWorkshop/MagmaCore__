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

class Utilities
{

    /**
     * Pull a particular property from each assoc. array in a numeric array, 
     * returning and array of the property values from each item.
     *
     *  @param array $a    Array to get data from
     *  @param string $prop Property to read
     *  @return array        Array of property values
     */
    static function pluck(array $a, string $prop): array
    {
        $out = array();

        for ($i = 0, $len = count($a); $i < $len; $i++) {
            $out[] = $a[$i][$prop];
        }

        return $out;
    }


    /**
     * Return a string from an array or a string
     *
     * @param array|string $a Array to join
     * @param string $join Glue for the concatenation
     * @return array|string Joined string
     */
    public static function _flatten($a, string $join = ' AND '): array|string
    {
        if (!$a) {
            return '';
        } else if (is_array($a)) {
            return implode($join, $a);
        }
        return $a;
    }

    /**
     * Convert an index base array to an dimensional array using the keys as the
     * value. Or alternatively combine 2 array for one multidimensional array.
     * At the end ensure all array keys are lowercase and replace any space
     * with an underscore.
     *
     * @param mixed $options
     * @return array
     */
    public static function arrayDimensional(mixed $options): array
    {
        $newOptions = array_combine(str_replace(' ', '_', $options), $options);
        return array_change_key_case($newOptions);
    }
}
