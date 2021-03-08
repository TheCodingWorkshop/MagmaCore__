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

namespace MagmaCore\Utility;

class Microtiming
{

    public function execution(callable $callback = null)
    {
        $time = microtime(true);
        if (is_callable($callback) && $callback !== null) {
            call_user_func($callback);
        }
        $time = microtime(true) - $time;
        return [
            $time . ' s',
            ($time * 1000) . ' ms'
        ];
    }
}
