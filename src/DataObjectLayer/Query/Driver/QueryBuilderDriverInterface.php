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

namespace MagmaCore\DataObjectLayer\Query\Driver;

interface QueryBuilderDriverInterface
{

    public function insertQueryDriver(): self;
    public function selectQueryDriver(): self;
    public function updateQueryDriver(): self;
    public function deleteQueryDriver(): self;
    public function rawQueryDriver(): self;

}
