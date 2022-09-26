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

namespace MagmaCore\Logger\Handler;

use MagmaCore\Logger\Exception\LoggerHandlerInvalidArgumentException;
use MagmaCore\Logger\LoggerTrait;

class NativeLoggerHandler extends AbstractLoggerHandler
{

    use LoggerTrait;

    private string $file;

    /**
     * NativeLoggerHandler constructor.
     * @param string $file
     * @param string $minLevel
     * @param array $options
     * @return void
     */
    public function __construct(string $file, string $minLevel, array $options = [])
    {
        parent::__construct($file, $minLevel, $options);

        if (!file_exists($this->getLogFile())) {
            if (!touch($this->getLogFile())) {
                throw new LoggerHandlerInvalidArgumentException('Log file ' . $this->getLogFile() . ' can not be created.');
            }
        }
        if (!is_writable($this->getLogFile())) {
            throw new LoggerHandlerInvalidArgumentException('Log file ' . $this->getLogFile() . ' is not writable.');
        }

    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function write(mixed $level, string $message, array $context = []): void
    {
        if (!$this->logLevelReached($level)) {
            return;
        }
        $line = $this->format($level, $message, $context);
        file_put_contents($this->getLogFile(), $line, FILE_APPEND | LOCK_EX);
    }

}
