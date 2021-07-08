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

use Exception;
use MagmaCore\Base\BaseApplication;
use MagmaCore\DataObjectLayer\DataRelationship\Exception\DataRelationshipInvalidArgumentException;

/**
 * Both tables can have only one record on each side of the relationship.
 * each primary key value relates to none or only one record in the related table
 */
class DataRelationship implements DataRelationshipInterface
{

    private object $table;
    private object $tableRight;
    private object $pivot;

    /**
     * Undocumented function
     *
     * @param string $relationship
     * @return static
     * @throws Exception
     */
    public function type(string $relationship): self
    {
        if ($relationship) {
            $relationship = BaseApplication::diGet($relationship);
            if (!$relationship) {
                throw new DataRelationshipInvalidArgumentException('');
            }
        }
        return $this;
    }

    /**
     * @param string $table
     * @param string|null $additionalTable
     * @return $this
     * @throws Exception
     */
    public function table(string $table, ?string $additionalTable = null): self
    {
        if (empty($table)) {
            throw new DataRelationshipInvalidArgumentException('Please specify the table.');
        }
        if ($table) {
            $this->table = BaseApplication::diGet($table);
            if (!$this->table) {
                throw new DataRelationshipInvalidArgumentException('');
            }
        }
        return $this;
    }

    /**
     * @param string $pivot
     * @return $this
     * @throws Exception
     */
    public function pivot(string $pivot): self
    {
        $this->pivot = $this->table($pivot);
        return $this;
    }

    /**
     * @return object
     */
    public function getPivot(): object
    {
        return $this->pivot;
    }

    /**
     * @return object
     */
    public function getTable(): object
    {
        return $this->table;
    }

}
