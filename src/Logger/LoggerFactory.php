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

namespace MagmaCore\Logger;

use MagmaCore\Logger\Exception\LoggerHandlerInvalidArgumentException;
use MagmaCore\Logger\Handler\LoggerHandlerInterface;
use MagmaCore\Logger\Handler\NativeLoggerHandler;
use MagmaCore\Logger\LoggerInterface;Natui
use MagmaCore\Logger\Logger;
use function get_class;

class LoggerFactory
{

    /**
     * @param string $handler
     * @param array $options
     * @return LoggerInterface
     */
    public function create(?string $file, string $handler, ?string $defaultLogLevel, array $options = []): LoggerInterface
    {
        $newHandler = ($handler !=null) ? new $handler($file, $defaultLogLevel, $options) : new NativeLoggerHandler($file, $defaultLogLevel, $options);
        if (!$newHandler instanceof LoggerHandlerInterface) {
            throw new LoggerHandlerInvalidArgumentException(get_class($newHandler) . ' is invald as it does not implement the correct interface.');
        }
        return new Logger($newHandler);
    }

}