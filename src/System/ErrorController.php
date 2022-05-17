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
        $this->addDefinitions(
            [
            ]
        );

    }

    protected function errorAction()
    {
        $this->render('client/error/error.html');
    }
    protected function missingControllerAction()
    {
        $this->render('client/error/error.html');
    }

    protected function error500Action()
    {
        $this->render('client/error/error500.html');
    }

    protected function error404Action()
    {
        $this->render('client/error/error404.html');
    }

}