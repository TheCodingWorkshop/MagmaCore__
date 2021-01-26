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

namespace MagmaCore\Queuing;

interface QueuingInteface
{

    public function create(array $data) : void;
    public function quantity();
    public function delete(Object $item) : void;
    public function release(Object $item) : bool;

}