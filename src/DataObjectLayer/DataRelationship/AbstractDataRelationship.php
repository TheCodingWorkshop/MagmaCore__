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

namespace MagmaCore\DataObjectLayer\DataRelationship;

/**
 * Each record in both tables can relate to none or any number of records 
 * in the other table. These relationships require a third table, 
 * called an associate or linking table, because relational systems cannot 
 * directly accommodate the relationship.
 */
abstract class AbstractDataRelationship implements DataRelationshipInterface
{

    protected object $relatableModel;
    protected string $tableSchemaPrimary;
    protected string $tableSchemaSecondary;
    protected mixed $fields;

    public function __construct(object $relatableModel)
    {
        $this->relatableModel = $relatableModel;
    }

    public function table(): static
    {
        $tables = $this->relatableModel->getSchema();
        $extract = explode('_', $tables);
        if (is_array($extract) && count($extract) > 1) {
            if (isset($extract[0]) && isset($extract[1])) {
                $this->tableSchemaPrimary = Stringify::pluralize($extract[0]);
                $this->tableSchemaSecondary = Stringify::pluralize($extract[1]);
                $this->tableResolver($this->tableSchemaPrimary, $this->tableSchemaSecondary);
            }
        }
    }

    public function set(mixed $fields): static
    {
        $this->fields = $fields;
        return new static($this->fields);
    }

    public function read(): static
    {

    }

    public function get(): static
    {

    }

    public function delete(): static
    {

    }

    public function push(): bool
    {
        if (count($this->fields) > 0) {
            $this->relatableModel
                ->getRepo()
                    ->getEm()
                        ->getCrud()
                            ->create($this->fields)
        }
    }

    private function tableResolver(string $tableSchemaPrimary, string $tableSchemaSecondary)
    {

    }


}