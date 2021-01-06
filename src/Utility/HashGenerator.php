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

use MagmaCore\Utility\Token;

class HashGenerator
{

    /**
     * Generate an activation hash string. When a new item needs hashing before entering
     * teh database.
     * 
     * @return array
     */
    public static function hash() : array
    {

        $token = new Token();
        $tokenhash = $token->getHash();
        $activationHash = $token->getValue();

        return [
            $tokenhash, 
            $activationHash
        ];

    }

}
