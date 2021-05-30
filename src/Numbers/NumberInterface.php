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

interface NumberInterface
{

    public function addNumber(mixed $num);
    public function number();
    public function addition(mixed $num = null);
    public function subtraction(mixed $num = null);
    public function division(mixed $num = null);
    public function multiplication(mixed $num = null);
    public function percentage(mixed $percentage);
    public function fraction();
}
