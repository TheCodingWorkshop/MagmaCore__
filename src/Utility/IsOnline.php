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

class IsOnline
{

    private static $msg;

    public static function online()
    {
        // switch (connection_status())
        // {
        // case CONNECTION_NORMAL:
        //   self::$msg = 'You are connected to internet.';
        //  // return true;
        //   break;
        // case CONNECTION_ABORTED:
        //   self::$msg = 'No Internet connection';
        //   //return false;
        //   break;
        // case CONNECTION_TIMEOUT:
        //   self::$msg = 'Connection time-out';
        //   //return false;
        //   break;
        // case (CONNECTION_ABORTED & CONNECTION_TIMEOUT):
        //   self::$msg = 'No Internet and Connection time-out';
        //  // return false;
        //   break;
        // default:
        //   self::$msg = 'Undefined state';
        //   //return false;
        //   break;
        // }
        //display connection status
        //return self::$msg;
    }

    public static function checkOnline(string $domain = 'http://www.google.com')
    {
        $file = @fsockopen ($domain, 80);//@fsockopen is used to connect to a socket
        if ($file) {
            return true;
        }
        return false;
    }

    public static function getConnectionMsg()
    {
        return self::$msg;
    }
}
