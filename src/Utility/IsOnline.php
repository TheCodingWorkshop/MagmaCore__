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

class isOnline
{

    public function online()
    {
        $connected = fopen("http://www.google.com:80/", "r");
        if ($connected) {
            return true;
        } else {
            return false;
        }
    }
}
