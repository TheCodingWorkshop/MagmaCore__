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

interface MigrationInterface
{

    public function createMigrationTable(): void;
    public function saveMigration(array $fields): bool;
    public function getMigrations(array $conditions = []): array|null;
    public function createMigrationFromSchema();
    public function locateMigrationFiles(): array;
    public function migrate(string|null $position = null): void;

}