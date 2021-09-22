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

namespace MagmaCore\Numbers;

use MagmaCore\Numbers\NumberInterface;

class Number
{

    /**
     * Constructor promotion private number property
     * 
     * @param $number
     * @return void
     */
    public function __construct(private mixed $number = 0, private mixed $_number = null)
    {
        if ($number)
            $this->number = $number;
        if ($_number !== null)
            $this->_number = $_number;
    }

    public function addNumber(mixed $num)
    {
        $this->number = $num;
    }

    public function number()
    {
        return $this->number;
    }

    public function _number()
    {
        return $this->_number;
    }

    public function addition(mixed $num = null)
    {
        if ($this->number)
            return $this->number + $this->_number;
    }

    public function subtraction(mixed $num = null)
    {
        if ($this->number)
            return $this->number - $this->_number;
    }

    public function division(mixed $num = null)
    {
        if ($this->number)
            return $this->number / $this->_number;
    }

    public function multiplication(mixed $num = null)
    {
        if ($this->number)
            return $this->number * $this->_number;
    }

    /**
     * Get the percentage of a given value
     *
     * @param mixed $percentage
     * @return mixed
     */
    public function percentage(mixed $percentage): mixed
    {
        if ($this->number)
            //return ($percentage / 100) * $this->number;
            return ($percentage / $this->number) * 100;
    }

    /**
     * format a number
     *
     * @param float $number
     * @param integer $decimal
     * @param string|null $sep
     * @return string
     */
    public function format(float $number, int $decimal = 2, ?string $sep = '.'): string
    {
        return number_format($number, $decimal, $sep);
    }

    public function fraction()
    {
    }
}
