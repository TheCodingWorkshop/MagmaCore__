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

    public function arrayFlatten($array): array
    {
        $return = array();
        foreach ($array as $key => $value) {
            if (is_array($value)){ $return = array_merge($return, $this->arrayFlatten($value));}
            else {$return[$key] = $value;}
        }
        return $return;

    }

    /**
     * @param array $context
     * @return array|string
     */
    public static function flattenContext(array $context = []): array|string|null
    {
        if (is_array($context)) {
            foreach ($context as $con) {
                return $con;
            }
        }
        return null;
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

    public static function isSet(string $key = null, array $array = []): bool
    {
        return array_key_exists($key, $array) === true;
    }

    /**
     * Convert a string to a slug format, replacing space with hyphons 
     *
     * @param string|null $title
     * @return void
     */
    public static function titleSlugConverter(string $title =null): string
    {

        $title = strtolower($title);
        $title = preg_replace('/&.+;/', '', $title); // kill entities
        $title = preg_replace('/[^a-z0-9 -]/', '', $title);
        $title = preg_replace('/\s+/', ' ', $title);
        $title = trim($title);
        $title = str_replace(' ', '-', $title);
        
        return $title;
    }

    public static function escUrl(string $url = null): ?string
    {
				
		if ( '' == $url ) {
			return $url;
		}
		$url = preg_replace( '|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url );
		$strip = array('%0d', '%0a', '%0D', '%0A');
		$url = (string) $url;
		$count = 1;
		while ( $count ) {
			$url = str_replace($strip, '', $url, $count);
		}
			 
		$url = str_replace( ';//', '://', $url );
		$url = htmlentities( $url );
		$url = str_replace( '&amp;', '&#038;', $url );
		$url = str_replace( "'", '&#039;', $url );
			 
		if ( $url[0] !== '/' ) {
			// We're only interested in relative links from $_SERVER['PHP_SELF']
			return '';
		} else {
			return $url;
		}

        return null;
				
	}

    
}
