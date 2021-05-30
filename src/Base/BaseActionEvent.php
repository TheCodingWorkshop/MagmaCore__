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

use MagmaCore\EventDispatcher\Event;
use MagmaCore\Base\Contracts\BaseActionEventInterface;

/**
 * Base action event which all app event class can build from
 */
class BaseActionEvent extends Event implements BaseActionEventInterface
{

    /** @var string - name of the event */
    public const NAME = 'magmacore.base.base_action_event';
    /** @var array */
    private array $context;
    /** @var Object - the current controller object */
    private Object $controller;
    /** @var string - the controller method as a string */
    private string $method;

    /**
     * Main class constructor method. assigning properties to constructor arguments
     *
     * @param array $context - the usable data as an array
     * @param Object $controllerObject
     */
    public function __construct(string $method, array $context, Object $controllerObject)
    {
        $this->method = $method;
        $this->context = $context;
        $this->controller = $controllerObject;
    }

    /**
     * Returns the namespace method
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Returns the contextual data from the method
     * 
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Returns the current controller object with access to all its methods and property
     *
     * @return Object
     */
    public function getObject(): Object
    {
        return $this->controller;
    }


}