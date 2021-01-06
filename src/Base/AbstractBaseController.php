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

namespace MagmaCore\Base;

use MagmaCore\Service\Contracts\ServiceSubscriberInterface;
use MagmaCore\Container\ContainerInterface;
use MagmaCore\Session\SessionInterface;
use MagmaCore\Cache\CacheInterface;
use MagmaCore\Router\RouterInterface;
use MagmaCore\Session\Flash\FlashInterface;
use MagmaCore\Base\ControllerTrait;

abstract class AbstractBaseController implements ServiceSubscriberInterface
{

    use ControllerTrait;

    protected ContainerInterface $container;
    protected array $routeParams;

    public function __construct(array $routeParams)
    {
        if ($routeParams)
            $this->routeParams = $routeParams;
    }

    public function setContainer(ContainerInterface $container)
    {
        $previous = $this->container;
        $this->container = $container;
        return $previous;
    }

    public static function getSubscribedServices() : array
    {
        return [
            'session' => '?' . SessionInterface::class,
            'cache' => '?' . CacheInterface::class,
            'router' => '?' . RouterInterface::class,
            'flash' => '?' . FlashInterface::class
        ];
    }

}