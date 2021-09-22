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

    public function addNum(mixed $num);
    public function num();
    public function add(mixed $num = null);
    public function sub(mixed $num = null);
    public function divi(mixed $num = null);
    public function times(mixed $num = null);
    public function perc(mixed $percentage);
    public function frac();
    public function format(float $number, int $decimal = 2, ?string $sep = '.');
    public function numeric();
}
