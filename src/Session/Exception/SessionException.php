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

use MagmaCore\Base\Exception\BaseException;

class SessionException extends BaseException
{

    public string $message = 'An exception was thrown in retrieving the key from the session storage.';
}
