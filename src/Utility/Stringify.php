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

use JetBrains\PhpStorm\Pure;

class Stringify
{

    private static function translateString(string $string): string
    {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
        if ($text) {
            return $text;
        }
    }

    /**
     * @param $text
     * @return string|string[]
     */
    public static function slugify($text): array|string
    {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text); // replace non letter or digits by -
        $text = trim($text, '-');
        $text = self::translateString($text); // transliterate
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text); // remove unwanted characters
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    /**
     * Convert a string into words, to use for labels and menu names
     *
     * @param $string - string to replace
     * @param string $atts
     * @return string
     * @since 1.0.0
     */
    public static function justify($string, string $atts = '')
    {
        if (empty($string))
            return;

        $search = array('-', '_', '[]', '[', ']');
        $replace_search = array(' ', ' ', '', ' ', '');
        $str_replace = str_replace($search, $replace_search, $string);
        /* Capitalize the first letter */
        if ('ucwords' == $atts)
            return ucwords(str_replace($search, $replace_search, $string));
        elseif ('strtolower' == $atts)
            return strtolower(str_replace($search, $replace_search, $string));
    }

    /**
     * pluralize the string if necessary
     *
     * @return 		string
     * @uses 		strlen & substr
     * @param 		$string
     */
    public static function pluralize($string): string
    {

        $last = $string[strlen($string) - 1];
        if ($last == 'y') {
            $cut = substr($string, 0, -1);
            $plural = $cut . 'ies'; //convert y to ies
        } else {
            $plural = $string . 's'; // just attach an s
        }

        return $plural;
    }

    /**
     * @param $string
     * @param bool $full
     * @return bool|string
     */
    #[Pure] public static function capitalize($string, bool $full = false): bool|string
    {
        if (!empty($string)) {
            // transliterate
            $text = self::translateString($string);
            $text = $full ? strtoupper($text) : ucwords($text);
            //$text = preg_replace('~[^-\w]+~', '', $text);
            if (empty($text)) {
                return "n-a";
            }

            return $text;
        }
        return false;
    }

    public static function studlyCaps(string $string) : string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
    }

    public static function camelCase(string $string) : string
    {
        return lcfirst(self::studlyCaps($string));
    }

    /**
     * Regular expression function that replaces spaces between words with hyphens.
     *
     * @param string $str - the string to convert
     * @return bool|string
     */
    public static function slugToUrl(string $str): bool|string
    {
        if (empty($str)) {
            return false;
        }
        return preg_replace('/[^A-Za-z0-9-]+/', '-', $str);
    }

    public static function endsWith(mixed $haystack, mixed $needle)
    {
//        $length = strlen($needle);
//        if (!$length) {
//            return true;
//        }
//        return substr_compare($haystack, $needle, -$length) === 0;
        return str_ends_with($haystack, $needle);

    }

}
