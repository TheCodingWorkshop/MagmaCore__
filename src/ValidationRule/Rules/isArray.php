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

class isArray extends ValidationRuleMethods
{

    public function isArray(object $controller, object $validationClass)
    {
        if (isset($validationClass->key)) {
            if (!is_array($validationClass->value)) {
                $this->getError(Error::display('err_invalid_array'), $controller, $validationClass);
            }
        }
    }
}
