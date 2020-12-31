<?php

declare(strict_types=1);

namespace MagmaCore\Session\GlobalManager;

interface GlobalManagerInterface
{

    /**
     * Set the global variable
     * 
     * @param string $name
     * @param mixed $context
     * @return void
     * @throws GlobalManagerException
     */
    public static function set(string $name, $context) : void;

    /**
     * Get the value/context of the set global variable
     * 
     * @param string $name
     * @return mixed
     * @throws GlobalManagerException
     */
    public static function get(string $name);

}