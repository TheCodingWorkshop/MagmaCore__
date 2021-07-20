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

class DateFormatter
{

    public const __SECONDS__ = 1;
    public const __MINUTE__ = 60 * self::__SECONDS__;
    public const __HOUR__ = 60 * self::__MINUTE__;
    public const __DAY__ = 24 * self::__HOUR__;
    public const __MONTH__ = 30 * self::__DAY__;

    /**
     * @param $time
     * @param bool $short
     * @return string
     */
    public static function timeFormat($time, $short = false)
    {
        $SECOND = 1;
        $MINUTE = 60 * $SECOND;
        $HOUR = 60 * $MINUTE;
        $DAY = 24 * $HOUR;
        $MONTH = 30 * $DAY;
        $before = time() - strtotime($time);

        if ($before < 0) {
            return "not yet";
        }

        if ($short) {
            if ($before < 1 * $MINUTE) {
                return ($before < 5) ? "just now" : $before . "s ago";
            }

            if ($before < 2 * $MINUTE) {
                return "1m ago";
            }

            if ($before < 45 * $MINUTE) {
                return floor($before / 60) . "m ago";
            }

            if ($before < 90 * $MINUTE) {
                return "1h ago";
            }

            if ($before < 24 * $HOUR) {

                return floor($before / 60 / 60) . "h ago";
            }

            if ($before < 48 * $HOUR) {
                return "1d ago";
            }

            if ($before < 30 * $DAY) {
                return floor($before / 60 / 60 / 24) . "d ago";
            }

            if ($before < 12 * $MONTH) {
                $months = floor($before / 60 / 60 / 24 / 30);
                return $months <= 1 ? "1mo ago" : $months . "mo ago";
            } else {
                $years = floor($before / 60 / 60 / 24 / 30 / 12);
                return $years <= 1 ? "1y ago" : $years . "y ago";
            }
        }

        if ($before < 1 * $MINUTE) {
            return ($before <= 1) ? "just now" : $before . " seconds ago";
        }

        if ($before < 2 * $MINUTE) {
            return "a minute ago";
        }

        if ($before < 45 * $MINUTE) {
            return floor($before / 60) . " minutes ago";
        }

        if ($before < 90 * $MINUTE) {
            return "an hour ago";
        }

        if ($before < 24 * $HOUR) {

            return (floor($before / 60 / 60) == 1 ? 'about an hour' : floor($before / 60 / 60) . ' hours') . " ago";
        }

        if ($before < 48 * $HOUR) {
            return "yesterday";
        }

        if ($before < 30 * $DAY) {
            return floor($before / 60 / 60 / 24) . " days ago";
        }

        if ($before < 12 * $MONTH) {

            $months = floor($before / 60 / 60 / 24 / 30);
            return $months <= 1 ? "one month ago" : $months . " months ago";
        } else {
            $years = floor($before / 60 / 60 / 24 / 30 / 12);
            return $years <= 1 ? "one year ago" : $years . " years ago";
        }

        return "$time";
    }

    /** @var array - units */
    protected array $units = ['b','kb','mb','gb','tb','pb'];

    public function bytes(int $size): string
    {
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '. $this->unit[$i];
    }

    public static function formatPeriod($end, $start): string
    {
        $duration = $end - $start;
        $hours = (int) ($duration / 60 / 60);
        $minutes = (int) ($duration / 60) - $hours * 60;
        $seconds = (int) ($duration - $hours * 60 * 60 - $minutes * 60);
        return ($hours == 0 ? "00" : $hours) . ":" . ($minutes == 0 ? "00" : ($minutes < 10 ? "0" . $minutes:$minutes)) . ":" . ($seconds == 0 ? "00" : ($seconds < 10 ? "0" . $seconds:$seconds));
    }

}
