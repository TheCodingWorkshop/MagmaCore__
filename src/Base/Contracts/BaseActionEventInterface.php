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

namespace MagmaCore\Base\Contracts;

/**
 * Base action event which all app event class can build from
 */
interface BaseActionEventInterface
{

    /**
     * Main class constructor method. assigning properties to constructor arguments
     *
     * @param array $context - the usable data as an array
     * @param object $controllerObject
     */
    public function __construct(string $method, array $context, Object $controllerObject);

    /**
     * Returns the namespace method
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Returns the contextual data from the method
     * 
     * @return array
     */
    public function getContext(): array;

    /**
     * Returns the current controller object with access to all its methods and property
     *
     * @return object
     */
    public function getObject(): Object;
}
