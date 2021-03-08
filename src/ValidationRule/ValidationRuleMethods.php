<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

namespace MagmaCore\ValidationRule;

use MagmaCore\Error\Error;

class ValidationRuleMethods
{

    /**
     * Undocumented function
     *
     * @param string $key
     * @param mixed $value
     * @param object $model
     * @return void
     */
    public function required(string $key, mixed $value, object $model, object $controller)
    {
        if (isset($key)) {
            if (empty($value) or $value === '') {
                if ($controller->error) {
                    $controller->error->addError(Error::display('err_field_require'), $controller)->dispatchError('/admin/role/index');
                }
            }    
        }

    }

    /**
     * Undocumented function
     *
     * @param string $key
     * @param mixed $value
     * @param object $object
     * @return void
     */
    public function unique(string $key, mixed $value, object $model, object $controller)
    {
        if (isset($key)) {
            $result = $model->findObjectBy([$key => $value], ['id']);
            if ($result) {
                if ($controller->error) {
                    $controller->error->addError(['error' => 'Name already exists'], $controller)->dispatchError();
                }
            }
        }
    }

}