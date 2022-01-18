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

namespace MagmaCore\ValidationRule;

class ValidationRuleMethods
{

    protected array $errors = [];
    /**
     * Main constructor class
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Dispatch the validation error
     *
     * @param string $msg
     * @param object $controller
     * @param object $validationClass
     * @return void
     */
    public function getError(string $msg, object $controller, object $validationClass)
    {
        $controller->flashMessage($msg, $controller->flashWarning());
        $controller->redirect($controller->onSelf());

        // if (isset($controller->error)) {
        //     $controller
        //         ->error
        //         ->addError($this->errors, $controller)
        //         ->dispatchError(
        //             ($validationClass->validationRedirect() !== '') ? $validationClass->validationRedirect() :
        //                 $controller->onSelf()
        //         );

        // }
    }
}
