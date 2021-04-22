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

namespace MagmaCore\Session\Exception;

use MagmaCore\Base\Exception\BaseUnexpectedValueException;

class SessionUnexpectedValueException extends BaseUnexpectedValueException
{

    public function __construct(
        string $message = null,
        int $code = 0,
        BaseUnexpectedValueException $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
