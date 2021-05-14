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

class Password extends ValidationRuleMethods
{

    public function password(object $controller, object $validationClass, int $length)
    {
        $error = [];
        if (!empty($validationClass->value)) {
            if (strlen($validationClass->value) < $length) {
                $error = Error::display('err_password_length');
            }
            if (preg_match('/.*\d+.*/i', $validationClass->value) == 0) {
                $error = Error::display('err_password_letter');
            }
            if (preg_match('/.*[a-z]+.*/i', $validationClass->value) == 0) {
                $error = Error::display('err_password_letter');
            }
            $this->getError($error, $controller, $validationClass);
        }
    }
}
