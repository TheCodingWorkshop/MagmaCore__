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

use MagmaCore\Base\ControllerTrait;
use MagmaCore\Cache\CacheInterface;
use MagmaCore\Router\RouterInterface;
use MagmaCore\Session\SessionInterface;
use MagmaCore\Container\ContainerInterface;
use MagmaCore\Session\Flash\FlashInterface;
use MagmaCore\EventDispatcher\ListenerProviderInterface;
use MagmaCore\Service\Contracts\ServiceSubscriberInterface;

abstract class AbstractBaseController implements ServiceSubscriberInterface, ListenerProviderInterface
{
    /** @var Trait */
    use ControllerTrait;
    /** @var ContainerInterface */
    protected ContainerInterface $container;
    /** @var array */
    protected array $routeParams;

    /**
     * Abstract controller constructor method
     *
     * @param array $routeParams
     * @return void
     */
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
        ];
    }

    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     * @return iterable<callable>
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable
    {
        return [

        ];
    }   


    /**
     * Return the current controller name as a string
     * @return string
     */
    public function thisRouteController() : string
    {
         return $this->routeParams['controller'];
    }

    /**
     * Return the current controller action as a string
     * @return string
     */
    public function thisRouteAction() : string
    {
        return $this->routeParams['action'];
    }

    /**
     * Return the current controller namespade as a string
     * @return string
     */
    public function thisRouteNamespace() : string
    {
        return isset($this->routeParams['namespace']) ? $this->routeParams['namespace'] : '';
    }

    /**
     * Return the current controller token as a string
     * @return string
     */
    public function thisRouteToken() : string|null
    {
        $token = isset($this->routeParams['token']) ? $this->routeParams['token'] : null;
        $token = (string)$token;
        return $token;

    }

    /**
     * Return the current controller route ID if set as a int
     * @return int|false
     */
    public function thisRouteID() : int|false
    {

        $ID = isset($this->routeParams['id']) ? $this->routeParams['id'] : false;
        $ID = intval($ID);
        return $ID;
    }

    public function toArray(Object $data)
    {
        return (array)$data;
    }


}