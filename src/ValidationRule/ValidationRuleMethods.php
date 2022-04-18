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

/**
 * Handles all erorrs coming through the core validation classes ie UserValidate, PostValidate etc..
 */
class ValidationRuleMethods
{
    
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

//         if (isset($controller->error)) {
//             $controller
//                 ->error
//                 ->addError(array_values($this->errors)[0], $controller)
//                 ->dispatchError(
//                     ($validationClass->validationRedirect() !== '') ? $validationClass->validationRedirect() :
//                         $controller->onSelf()
//                 );
//
//         }
    }
}
