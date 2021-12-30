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

class isString extends ValidationRuleMethods
{

    public function isString(object $controller, object $validationClass)
    {
        if (isset($validationClass->key)) {
            if (!is_string($validationClass->value)) {
                $this->getError(array_values(Error::display('err_invalid_string'))[0], $controller, $validationClass);
            }
        }
    }
}
