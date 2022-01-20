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

class Serializer
{

    /**
     * Serialize data, if needed.
     *
     * @param mixed $data Data that might be serialized.
     * @return mixed A scalar data.
     */
    public static function compress(mixed $data) {
        if ( is_array( $data ) || is_object( $data ) ) {
            return serialize( $data );
        }

        /*
        * Double serialization is required for backward compatibility.
        * See https://core.trac.wordpress.org/ticket/12930
        * Also the world will end. See WP 3.6.1.
        */
        if ( self::isSerialized( $data, false ) ) {
            return serialize( $data );
        }

        return $data;
    }


    /**
     * Unserialize data only if it was serialized.
     *
     * @param string $data Data that might be unserialized.
     * @return mixed Unserialized data can be any type.
     */
    public static function unCompress(mixed $data) {
        if ( self::isSerialized( $data ) ) { // Don't attempt to unserialize data that wasn't serialized going in.
            return @unserialize( trim( $data ) );
        }

        return $data;
    }

    /**
     * Check value to find if it was serialized.
     *
     * If $data is not an string, then returned value will always be false.
     * Serialized data is always a string.
     *
     * @since 2.0.5
     *
     * @param string $data   Value to check to see if was serialized.
     * @param bool   $strict Optional. Whether to be strict about the end of the string. Default true.
     * @return bool False if not serialized and true if it was.
     */
    public static function isSerialized( $data, $strict = true ) {
        // If it isn't a string, it isn't serialized.
        if ( ! is_string( $data ) ) {
            return false;
        }
        $data = trim( $data );
        if ( 'N;' === $data ) {
            return true;
        }
        if ( strlen( $data ) < 4 ) {
            return false;
        }
        if ( ':' !== $data[1] ) {
            return false;
        }
        if ( $strict ) {
            $lastc = substr( $data, -1 );
            if ( ';' !== $lastc && '}' !== $lastc ) {
                return false;
            }
        } else {
            $semicolon = strpos( $data, ';' );
            $brace     = strpos( $data, '}' );
            // Either ; or } must exist.
            if ( false === $semicolon && false === $brace ) {
                return false;
            }
            // But neither must be in the first X characters.
            if ( false !== $semicolon && $semicolon < 3 ) {
                return false;
            }
            if ( false !== $brace && $brace < 4 ) {
                return false;
            }
        }
        $token = $data[0];
        switch ( $token ) {
            case 's':
                if ( $strict ) {
                    if ( '"' !== substr( $data, -2, 1 ) ) {
                        return false;
                    }
                } elseif ( false === strpos( $data, '"' ) ) {
                    return false;
                }
                // Or else fall through.
            case 'a':
            case 'O':
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';
                return (bool) preg_match( "/^{$token}:[0-9.E+-]+;$end/", $data );
        }
        return false;
    }

    /**
     * Check whether serialized data is of string type.
     *
     * @since 2.0.5
     *
     * @param string $data Serialized data.
     * @return bool False if not a serialized string, true if it is.
     */
    function isSerializedString( $data ) {
        // if it isn't a string, it isn't a serialized string.
        if ( ! is_string( $data ) ) {
            return false;
        }
        $data = trim( $data );
        if ( strlen( $data ) < 4 ) {
            return false;
        } elseif ( ':' !== $data[1] ) {
            return false;
        } elseif ( ';' !== substr( $data, -1 ) ) {
            return false;
        } elseif ( 's' !== $data[0] ) {
            return false;
        } elseif ( '"' !== substr( $data, -2, 1 ) ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the week start and end from the datetime or date string from MySQL.
     *
     * @since 0.71
     *
     * @param string     $mysqlstring   Date or datetime field type from MySQL.
     * @param int|string $start_of_week Optional. Start of the week as an integer. Default empty string.
     * @return array Keys are 'start' and 'end'.
     */
    function get_weekstartend( $mysqlstring, $start_of_week = '' ) {
        // MySQL string year.
        $my = substr( $mysqlstring, 0, 4 );

        // MySQL string month.
        $mm = substr( $mysqlstring, 8, 2 );

        // MySQL string day.
        $md = substr( $mysqlstring, 5, 2 );

        // The timestamp for MySQL string day.
        $day = mktime( 0, 0, 0, $md, $mm, $my );

        // The day of the week from the timestamp.
        $weekday = gmdate( 'w', $day );

        if ( ! is_numeric( $start_of_week ) ) {
            $start_of_week = get_option( 'start_of_week' );
        }

        if ( $weekday < $start_of_week ) {
            $weekday += 7;
        }

        // The most recent week start day on or before $day.
        $start = $day - DAY_IN_SECONDS * ( $weekday - $start_of_week );

        // $start + 1 week - 1 second.
        $end = $start + WEEK_IN_SECONDS - 1;
        return compact( 'start', 'end' );
    }

}