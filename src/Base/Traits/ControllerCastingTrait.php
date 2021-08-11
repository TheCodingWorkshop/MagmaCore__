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

namespace MagmaCore\Base\Traits;

trait ControllerCastingTrait
{

    /**
     * @param $value
     * @return array
     */
    public function toArray($value): array
    {
        if ($value)
            return (array)$value;
    }

    /**
     * @param $value
     * @return int
     */
    public function toInt($value): int
    {
        if ($value)
            return (int)$value;
    }

    /**
     * @param $value
     * @return object
     */
    public function toObject($value): object
    {
        if ($value)
            return (object)$value;
    }

    /**
     * @param $value
     * @return string
     */
    public function toString($value): string
    {
        if ($value)
            return (string)$value;
    }

    /**
     * @param $value
     * @return bool
     */
    public function toBool($value): bool
    {
        if ($value)
            return (bool)$value;
    }

    /**
     * @param $value
     * @return null
     */
    public function toNull($value)
    {
        if (empty($value))
            return null;
    }

}
