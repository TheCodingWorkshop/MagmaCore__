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

use MagmaCore\Error\Error;

class ValidationRuleMethods
{
    /**
     * Main constructor class
     *
     * @return void
     */
    public function __construct()
    {}

    /**
     * Field is required validation rule
     *
     * @param object $controller
     * @param object $validationClass
     * @param mixed $args - optional arguments which gets pass from the validation class to the rule
     * @return void
     */
    public function required(object $controller, object $validationClass): void
    {
        if (isset($validationClass->key)) {
            if (empty($validationClass->key) || $validationClass->value === '') {
                $this->getError(
                    Error::display('err_field_require'), 
                    $controller, 
                    $validationClass);
            }
        }
    }

    /**
     * validation rule which checks the database for duplicate entry
     *
     * @param object $controller
     * @param object $validationClass
     * @param mixed $args - optional arguments which gets pass from the validation class to the rule
     * @return void
     */
    public function unique(object $controller, object $validationClass): void
    {
        if (isset($validationClass->key)) {
            $result = $controller->repository
            ->getRepo()
                ->findObjectBy([$validationClass->key => $validationClass->value],['*']);
                if ($result) {
                    $ignoreID = (!empty($controller->thisRouteID()) ? $controller->thisRouteID() : null);
                    if ($result->id == $ignoreID) {
                        $this->getError(
                            Error::display('err_data_exists'), 
                            $controller, 
                            $validationClass);

                    }
                }
        }
    }

    /**
     * valid email address require validation rule
     *
     * @param object $controller
     * @param object $validationClass
     * @param mixed $args - optional arguments which gets pass from the validation class to the rule
     * @return void
     */
    public function email(object $controller, object $validationClass)
    {
        if (isset($validationClass->key)) {
            if (filter_var($validationClass->value, FILTER_VALIDATE_EMAIL) === false) {
                $this->getError(Error::display('err_invalid_email'), $controller, $validationClass);
            }
        }
    }
    
    /**
     * Undocumented function
     *
     * @param object $controller
     * @param object $validationClass
     * @param mixed $args - optional arguments which gets pass from the validation class to the rule
     * @return void
     */
    public function password(object $controller, object $validationClass, int $length)
    {
        $error = [];
        if (!empty($validationClass->value)) {
            if (strlen($validationClass->value) < $length) {
                $error = Error::display('err_password_length');
            }
            if ( preg_match('/.*\d+.*/i', $validationClass->value) == 0) {
                $error = Error::display('err_password_letter');
            }
            if (preg_match('/.*[a-z]+.*/i', $validationClass->value) == 0) {
                $error = Error::display('err_password_letter');
            }
            $this->getError($error, $controller, $validationClass);
        }
    }

    public function passwordEqual(object $controller, object $validationClass, int $length)
    {
        /*if ($cleanData->client_password_hash === $cleanData->password_hash_retype) {
            return true;
        }*/

    }

    /**
     * Dispatch the validation error
     *
     * @param array $msg
     * @param object $controller
     * @param object $validationClass
     * @return void
     */
    public function getError(array $msg, object $controller, object $validationClass): void
    {
        if ($controller->error) {
            $controller
                ->error
                    ->addError($msg, $controller)
                        ->dispatchError(
                            ($validationClass->validationRedirect() !=='') ?$validationClass->validationRedirect() : 
                            $controller->onSelf());
        }
    }

}
