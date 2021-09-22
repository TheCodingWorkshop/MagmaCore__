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

use MagmaCore\StringCollection\StringCollectionInterface;

class StringCollection
{

    /**
     * Undocumented function
     *
     * @param string $str
     */
    public function __construct(string $str)
    {
        if ($str)
            $this->str = (string)$str;
    }

    /**
     * Simple return the raw string
     *
     * @return string
     */
    public function raw(): string
    {
        return $this->str;
    }

    public function upper(): string
    {
        return ucfirst($this->str);
    }
}
