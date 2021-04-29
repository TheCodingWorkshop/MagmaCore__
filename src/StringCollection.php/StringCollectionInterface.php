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

namespace MagmaCore\StringCollection;

interface StringCollectionInterface
{
    
    public function raw(): string;
    public function replace();
    public function trim();
    public function upper();
    public function lower();
    public function capitalize();
    public function wrap();
    public function position();
    public function length();
    public function compare();
    public function contains();
    public function specialChars();
    public function chunk();
    public function join();
    public function flower();
    public function parse();
    public function sprint();
    public function print();

}
