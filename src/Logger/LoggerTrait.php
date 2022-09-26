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

use function array_search;
use function get_class;
use function gettype;
use DateTimeInterface;
use DateTime;

trait LoggerTrait
{

    /**
     * @param string $level
     * @return bool
     */
    public function logLevelReached(string $level): bool
    {
        return array_search($level, $this->getLogLevels()) >= array_search($this->getMinLogLevel(), $this->getLogLevels());
    }

    /**
     * Interpolates context values into the message placeholders.
     */
    public function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if ($val !=null && !is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            } elseif ($val instanceof DateTimeInterface) {
                $replace['{' . $key . '}'] = $val->format(DateTime::RFC3339);
            } elseif (is_object($val)) {
                $replace['{' . $key . '}'] = '[object ' . get_class($val) . ']';
            } else {
                $replace['{' . $key . '}'] = '[' . gettype($val) . ']';
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

    /**
     * @param $level
     * @param $message
     * @param array $context
     * @param null $timestamp
     * @return string
     */
    public function format($level, $message, array $context = [], $timestamp = null): string
    {
        if ($timestamp == null) {
            $timestamp = date('Y-m-d H:i:s');
        }
        return '[' . $timestamp . '] ' . strtoupper($level) . ':' . $this->interpolate($message, $context) . "\n";
    }
}
