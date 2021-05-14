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

class Required extends ValidationRuleMethods
{

    public function required(object $controller, object $validationClass)
    {
        if (isset($validationClass->key)) {
            //if (strlen($validatedClass->value) === 0) {}
            if (empty($validationClass->key) || $validationClass->value === '') {
                $this->getError(Error::display('err_field_require'), $controller, $validationClass);
            }
        }
    }
}
