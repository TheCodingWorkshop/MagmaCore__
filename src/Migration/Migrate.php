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

namespace MagmaCore\Migration;

class Migrate
{

    public const NEED_MIGRATION = 'You\'ve not created any migration files yet!';
    public const CREATE_MIGRATION = 'Generating mirgation for...';
    public const END_MIGRATION = ' ... OK';

    public const FILES_ALTERING = ['Drop', 'Change', 'Add', 'Modify'];
    public const MIGRATE_DESTROY = 'Destroy';
    public const MIGRATE_UP = 'up';
    public const MIGRATE_DOWN = 'down';
    public const MIGRATE_CHANGE = 'change';


}