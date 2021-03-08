<?php

declare(strict_types=1);

namespace MagmaCore\ValidationRule;

class ValidationRuleMethods
{

    public function string($key, $object)
    {
        if (!is_string($key)) {
            return [
                'err_field_required',
                'This field is required'
            ]
        }
    }

    public function required($key, $object)
    {
        if (!empty($key)) {
            return [

            ]
        }
    }

}