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

    /**
     * Returns an error if one or more field is empty.
     *
     * @param object $controller
     * @param object $validationClass
     * @return void
     */
    public function required(object $controller, object $validationClass): void
    {
        if (isset($validationClass->key)) {
            if ($validationClass->value === '') {
                $this->getError(array_values(Error::display('err_field_require'))[0], $controller, $validationClass);
            }
        }
    }
}
