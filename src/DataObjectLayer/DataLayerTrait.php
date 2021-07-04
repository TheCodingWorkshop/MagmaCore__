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

namespace MagmaCore\DataObjectLayer;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

trait DataLayerTrait
{

    /**
     * Returns a flatten array from a multidimensional array
     *
     * @param array|null $array $array the multidimensional array
     * @return array
     */
    public function flattenArray(array $array = null): array
    {
        if (is_array($array)) {
            $arraySingle = [];
            foreach ($array as $arr) {
                foreach ($arr as $val) {
                    $arraySingle[] = $val;
                }
            }
            return $arraySingle;
        }
    }

    /**
     * Returns a flatten array from a multidimensional array recursively
     *
     * @param array|null $array $array the multidimensional array
     * @return array
     */
    public function flattenArrayRecursive(array $array = null): array
    {
        $flatArray = array();
        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $value) {
            $flatArray[] = $value;
        }
        return $flatArray;
    }

}
