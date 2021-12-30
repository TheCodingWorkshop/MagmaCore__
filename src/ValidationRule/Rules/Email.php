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

namespace MagmaCore\ValidationRule\Rules;

use MagmaCore\Error\Error;
use MagmaCore\ValidationRule\ValidationRuleMethods;

class Email extends ValidationRuleMethods
{

    /**
     * Returns an error if the email field is invalid
     *
     * @param object $controller
     * @param object $validationClass
     * @return void
     */
    public function email(object $controller, object $validationClass): void
    {
        if (isset($validationClass->key)) {
            if (filter_var($validationClass->value, FILTER_VALIDATE_EMAIL) === false) {
                $this->getError(array_values(Error::display('err_invalid_email'))[0], $controller, $validationClass);
            }
        }
    }
}
