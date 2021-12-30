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

class Unique extends ValidationRuleMethods
{

    public function unique(object $controller, object $validationClass)
    {
        if (isset($validationClass->key)) {
            $result = $controller->repository
                ->getRepo()
                ->findObjectBy([$validationClass->key => $validationClass->value], ['*']);
            if ($result) {
                $ignoreID = (!empty($controller->thisRouteID()) ? $controller->thisRouteID() : null);
                if ($result->id == $ignoreID) {
                    $this->getError(array_values(Error::display('err_data_exists'))[0], $controller, $validationClass);
                }
            }
        }
    }
}
