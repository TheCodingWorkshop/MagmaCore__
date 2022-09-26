<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types=1);

namespace MagmaCore\System;

use MagmaCore\Base\BaseController;

class ErrorController extends BaseController
{

    /**
     * Extends the base constructor method. Which gives us access to all the base
     * methods implemented within the base controller class.
     * Class dependency can be loaded within the constructor by calling the
     * container method and passing in an associative array of dependency to use within
     * the class
     *
     * @param array $routeParams
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
 
    }

    protected function errorAction()
    {
        $session = $this->getSession();
        $this->render(
            'client/error/error.html',
            [
                'invalid_route_request' => $session->get('invalid_route_request'),
                'invalid_controller_request' => $session->get('invalid_controller_request'),

            ]
        );
    }
    protected function errormAction()
    {
        $session = $this->getSession();
        $this->render(
            'client/error/errorm.html',
            [
                'invalid_method_request' => $session->get('invalid_method_request'),
                'route_controler_object' => $session->get('route_controler_object'),

            ]
        );
    }
    protected function erroraAction()
    {
        $session = $this->getSession();
        $this->render(
            'client/error/errora.html',
            [
                'invalid_method' => $session->get('invalid_method'),

            ]
        );
    }

}