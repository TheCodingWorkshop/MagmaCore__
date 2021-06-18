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

abstract class Singleton
{

    /**
     * The Singleton's instance is stored in a static field. This field is an
     * array, because we'll allow our Singleton to have subclasses. Each item in
     * this array will be an instance of a specific Singleton's subclass.
     */
    private static array $instance = [];

    /**
     * Cloning and unserialization are not permitted for singletons
     */
    protected final function __clone(){}

    /**
     * @throws \Exception
     */
    protected final function __wakeup()
    { 
        throw new \Exception("Cannot unserialize a singleton.");
    }
    
    /**
     * This is the static method that controls the access to the singleton
     * instance. On the first run, it creates a singleton object and places it
     * into the static field. On subsequent runs, it returns the client existing
     * object stored in the static field.
     *
     * This implementation lets you subclass the Singleton class while keeping
     * just one instance of each subclass around.
     */
    public final static function getInstance() : Singleton
    {
        $subClass = static::class;
        if (!isset(self::$instance[$subClass])) {
            self::$instance[$subClass] = new static();
        }
        return self::$instance[$subClass];
    }

    abstract public function initialize() : Object;
 
}