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

namespace MagmaCore\Administrator\Middleware\Before;

use Closure;
use http\Exception\InvalidArgumentException;
use MagmaCore\Middleware\BeforeMiddleware;
use MagmaCore\Utility\Stringify;

class IntegrityConstraints extends BeforeMiddleware
{

    /**
     * Integrity constraints middleware intercept the request the check the database if a certain
     * column already exists. will return an error on true or success on false
     *
     * @param Object $middleware - contains the BaseController object
     * @param Closure $next
     * @return void
     */
    public function middleware(object $middleware, Closure $next)
    {
        /* Which controller is posting data */
        $controller = $middleware->thisRouteController();
        /* Get the post data array */
        $data = $middleware->formBuilder->getData();

        if ($controller === 'user') {
            return $next($middleware);
        }


        /* unset unwanted fields */
        unset($data['_CSRF_INDEX'], $data['_CSRF_TOKEN']); /* unset form tokens */
        unset($data['new-' . $controller]); /* unset the submit */

        $appController = $this->getAppController($controller);

        /* Get the database ready */
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $key => $value) {
                $controllerRepo = $this->getControllerRepo($appController, $middleware);
                $this->throwException($controllerRepo, $key, $value, $middleware);
                unset($controllerRepo);
            }
        }

        return $next($middleware);

    }

    /**
     * @param mixed $integrityConstraints
     * @param int|string $key
     * @param mixed $value
     * @param object $middleware
     */
    public function integrityConstraints(mixed $integrityConstraints, int|string $key, mixed $value, object $middleware): void
    {
        foreach ($integrityConstraints as $column => $columnValue) {

            if (in_array($key, array_keys($columnValue))) {
                if ($columnValue[$key] === $value) {

                    $middleware->flashMessage($value . ' already exists for database field ' . $key, $middleware->flashWarning());
                    $middleware->redirect($middleware->onSelf());
                }
            }

        }
    }

    /**
     * @param string $appController
     * @param object $middleware
     * @return mixed
     */
    public function getControllerRepo(string $appController, object $middleware): mixed
    {
        $controllerRepo = new $appController($middleware->getRouteParams());
        return $controllerRepo;
    }

    /**
     * @param $controller
     * @return string
     */
    public function getAppController($controller): string
    {
        $controller = Stringify::studlyCaps($controller . 'Controller');
        $appController = '\App\Controller\Admin\\' . $controller;
        return $appController;
    }

    /**
     * @param mixed $controllerRepo
     * @param int|string $key
     * @param mixed $value
     * @param object $middleware
     */
    public function throwException(mixed $controllerRepo, int|string $key, mixed $value, object $middleware): void
    {
        if (isset($controllerRepo->repository)) {
            if (!in_array($key, $controllerRepo->repository->getFillables())) {
                throw new InvalidArgumentException($key . ' does not exists within the database table');
            }
            /* check for integrity constraints */
            $integrityConstraints = $controllerRepo->repository->getRepo()->findAll();
            $this->integrityConstraints($integrityConstraints, $key, $value, $middleware);
        }
    }

}
