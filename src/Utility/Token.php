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

use MagmaCore\Utility\Yaml;

class Token
{

    /** @var string */
    protected string $token;

    /**
     * Class constructor. Create a new random token or assign an existing one if passed in.
     *
     * @param string|null $tokenValue
     * @param int $bytes
     * @throws Exception
     */
    public function __construct(string $tokenValue = null, int $bytes = 16)
    {
        if ($tokenValue) {
            $this->token = $tokenValue;
        } else {
            $this->token = bin2hex(random_bytes($bytes));
        }
    }

    /**
     * Get the token value
     * 
     * @return string the token value
     * @throws Exception
     */
    public function getValue() : string
    {
        return $this->token;
    }

    /**
     * Get the hashed token value
     *
     * @return string The hashed value
     * @throws Exception
     */
    public function getHash() : string
    {
        return hash_hmac('sha256', $this->token, Yaml::file('app')['settings']['secret_key']);
    }

}