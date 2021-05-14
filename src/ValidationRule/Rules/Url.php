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

class Url extends ValidationRuleMethods
{

    public function url(object $controller, object $validationClass)
    {
        if (isset($validationClass->key)) {
            if (filter_var($validationClass->value, FILTER_VALIDATE_URL) === false) {
                $this->getError(Error::display('err_invalid_url'), $controller, $validationClass);
            }
        }
    }
}
