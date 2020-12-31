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

namespace MagmaCore\Container;

use MagmaCore\Container\Exception\ContainerInvalidArgumentException;
use MagmaCore\Container\ContainerInterface;
use MagmaCore\Container\Container;

/** PSR-11 Container */
class ContainerFactory
{ 

    /** @var array */
    protected array $providers = [];

    /** @return void */
    public function __construct() 
    { }

    /**
     * Factory method which creates the container object.
     *
     * @param string|null $container
     * @return ContainerInterface
     */
    public function create(?string $container = null) : ContainerInterface
    {
        $containerObject = ($container !=null) ? new $container() : new Container();
        if (!$containerObject instanceof ContainerInterface) {
            throw new ContainerInvalidArgumentException($container . ' is not a valid container object');
        }
        return $containerObject;
    }


}
