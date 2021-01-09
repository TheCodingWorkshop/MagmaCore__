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

namespace MagmaCore\Http\Event;

final class BaseEvents
{

    const REQUEST = 'base.request';
    const RESPONSE = 'base.response';
    const EXCEPTION = 'base.exception';
    const CONTROLLER = 'base.controller';
    const VIEW = 'base.view';

}
