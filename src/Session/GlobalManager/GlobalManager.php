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

namespace MagmaCore\Session\GlobalManager;

use MagmaCore\Session\GlobalManager\GlobalManagerInterface;
use MagmaCore\Session\GlobalManager\GlobalManagerException;

class GlobalManager implements GlobalManagerInterface
{

    /**
     * @inheritdoc
     * 
     * @param string $name
     * @param mixed $context
     * @return void
     * @throws GlobalManagerException
     */
    public static function set(string $name, $context): void
    {
        if ($name !== '') {
            $GLOBALS[$name] = $context;
        }
    }

    /**
     * @inheritdoc
     * 
     * @param string $name
     * @return mixed
     * @throws GlobalManagerException
     */
    public static function get(string $name)
    {
        self::isGlobalValid($name);
        try {
            return $GLOBALS[$name];
        } catch (GlobalManagerException $ex) {
            throw $ex;
        }
    }

    /**
     * Check whether the global name is set else throw an exception
     * 
     * @param string $name
     * @return void
     */
    protected static function isGlobalValid(string $name): void
    {
        if (!isset($GLOBALS[$name]) || empty($name)) {
            throw new GlobalManagerException("Invalid global. Please ensure you've set the global state for {$name}");
        }
    }
}
