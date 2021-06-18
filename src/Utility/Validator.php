<?php

declare(strict_types=1);

namespace MagmaCore\Utility;

class Validator
{

    /**
     * Undocumented function
     *
     * @param string $email
     * @return mixed
     */
    public static function email(string $email)
    {
        if (!empty($email)) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }
    }


}