<?php

declare(strict_types=1);

namespace MagmaCore\Base\Traits;

trait ControllerCastingTrait
{


    public function toArray($value): array
    {
        if ($value)
            return (array)$value;
    }

    public function toInt($value): int
    {
        if ($value)
            return (int)$value;
    }

    public function toObject($value): object
    {
        if ($value)
            return (object)$value;
    }

    public function toString($value): string
    {
        if ($value)
            return (string)$value;
    }

    public function toBool($value): bool
    {
        if ($value)
            return (bool)$value;
    }

    public function toNull($value)
    {
        if (empty($value))
            return null;
    }
}
