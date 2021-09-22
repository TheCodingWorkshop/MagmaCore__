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

namespace MagmaCore\Collections\Number;


class NumberCollection implements NumberCollectionInterface
{

    use NumberCollectionTrait;

    public function __construct(protected mixed $num = null)
    {
        $this->num = $num;
    }

    public function addNum(mixed $num): self
    {
        $this->num = $num;
        return $this;
    }

    public function num(): mixed
    {
        return $this->num;
    }

    public function has(): bool
    {
        return (isset($this->num)) ? true : false;
    }

    public function type(): string
    {
        return gettype($this->num);
    }

    public function add(mixed $num)
    {
        if ($this->num) {
            return $this->num + $num;
        }
    }

    public function sub(mixed $num)
    {
        if ($this->num) {
            return $this->num - $num;
        }
    }

    public function divi(mixed $num)
    {
        if ($this->num) {
            return $this->num / $num;
        }
    }

    public function times(mixed $num)
    {
        if ($this->num) {
            return $this->num * $num;
        }
    }

    public function perc(mixed $num, bool $reverse = false)
    {
        if ($this->num) {
            return ($reverse) ? ($num / $this->num) * 100 : ($this->num / $num) * 100; 
        }
    }

    public function frac()
    {
        if ($this->num) {

        }
    }

    public function format(float $number, int $decimal = 2, ?string $sep = '.'): string
    {
        return number_format($number, $decimal, $sep);
    }

    public function numeric(): bool
    {
        return is_numeric($this->num) ? true : false;
    }

    public function range(mixed $to): static
    {
        if ($this->num) {
            return new static(range($this->toInt($this->num), $to));
        }
    }

    public function toInt(): int
    {
        return (int)$this->num;
    }

    public function toFloat(): float
    {
        return (float)$this->num;
    }

}
