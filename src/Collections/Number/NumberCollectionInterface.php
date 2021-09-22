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

interface NumberCollectionInterface
{

    public function addNum(mixed $num): self;
    public function num();
    public function has(): bool;
    public function type(): string;
    public function add(mixed $num);
    public function sub(mixed $num);
    public function divi(mixed $num);
    public function times(mixed $num);
    public function perc(mixed $percentage, bool $reverse = false);
    public function frac();
    public function format(float $number, int $decimal = 2, ?string $sep = '.');
    public function numeric();
    public function range(mixed $to): static;
}
