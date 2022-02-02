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

namespace MagmaCore\DataObjectLayer\Query;

interface QueryBuilderInterface
{

    public function insertQuery(): string;
    public function selectQuery(): string;
    public function updateQuery(): string;
    public function deleteQuery(): string;
    public function rawQuery(): string;

}
