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

namespace MagmaCore\Base\Traits;
use ReflectionMethod;
use ReflectionClass;
use function PHPUnit\Framework\callback;

trait ControllerPrivilegeTrait
{

    /**
     * @param object $controller
     * @throws \ReflectionException
     */
    public function setControllerPrivilege(object $controller)
    {
        return $this->resolvedControllerPrivilege($controller);
    }

    /**
     * @param $controller
     * @throws \ReflectionException
     * @return void
     */
    public function resolvedControllerPrivilege($controller): void
    {
        $this->classNamespace = get_class($controller);
        $this->controller = $controller;
        $this->reflection = new ReflectionClass($this->classNamespace);
        $methods = $this->reflection->getMethods(ReflectionMethod::IS_PROTECTED);

        $this->getControllerMethods($methods);
    }

    /**
     * @param array $methods
     */
    private function getControllerMethods(array $methods)
    {
        if (count($methods) > 0) {
            foreach ($methods as $method) {
                $this->routeMethod = $method;
            }
        }
    }

    public function watchRoutes()
    {
        var_dump($this->routeMethod);
    }

}
