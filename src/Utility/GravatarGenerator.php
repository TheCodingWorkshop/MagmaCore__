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

use Exception;

class GravatarGenerator
{

    public static function Gravatar()
    {
        $str = file_get_contents('https://www.gravatar.com/205e460b479e2e5b48aec07710c08d50.php');
        $profile = unserialize($str);
        if (is_array($profile) && isset($profile['entry']))
            echo $profile['entry'][0]['displayName'];
    }

    /**
     *  Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param $email
     * @param bool $img
     * @param array $atts
     * @return String containing either just a URL or a complete image tag
     * @source https://gravatar.com/site/implement/images/php/
     * @throws Exception
     */
    public static function setGravatar($email, bool $img = false, array $atts = []): string
    {
        $gravatar = Yaml::file('app')['gravatar'];

        $s = $gravatar['size'];
        $d = $gravatar['default'];
        $r = $gravatar['rating'];

        $url = "https://www.gravatar.com/avatar/";
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }

        return $url;
    }

    public static function getGravatar(string $email, $default, $size): string
    {
        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
    }

}
