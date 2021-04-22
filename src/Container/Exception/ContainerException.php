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

namespace MagmaCore\Container\Exception;

use MagmaCore\Base\Exception\BaseException;
use MagmaCore\Container\ContainerExceptionInterface;

/** PSR-11 Container */
class ContainerException extends BaseException implements ContainerExceptionInterface
{
}
