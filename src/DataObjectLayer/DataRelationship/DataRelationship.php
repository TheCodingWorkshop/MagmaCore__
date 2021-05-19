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

use MagmaCore\Base\BaseApplication;
use MagmaCore\DataObjectLayer\DataRelationship\DataRelationshipInterface;

/**
 * Both tables can have only one record on each side of the relationship.
 * each primary key value relates to none or only one record in the related table
 */
class DataRelationship
{

    private object $tableLeft;
    private object $tableRight;
    private object $tablePivot;
    private object $relationship;

    public function __construct()
    {
    }

    /**
     * Undocumented function
     *
     * @param string $relationship
     * @return static
     */
    public function type(string $relationship): self
    {
        if ($relationship) {
            $this->relationship = BaseApplication::diGet($relationship);
            if (!$this->relationship) {
                throw new \Exception();
            }
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $tableLeft
     * @param string $tableRight
     * @return void
     */
    public function tables(string $tableLeft, string $tableRight): self
    {
        if (empty($tableLeft) || empty($tableRight)) {
            throw new \Exception();
        }

        if ($tableLeft) {
            $this->tableLeft = BaseApplication::diGet($tableLeft);
            if (!$this->tableLeft) {
                throw new \Exception();
            }
            if ($tableRight) {
                $this->tableRight = BaseApplication::diGet($tableRight);
                if (!$this->tableRight) {
                    throw new \Exception();
                }
            }
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $tablePivot
     * @return void
     */
    public function pivot(string $tablePivot): self
    {
        if ($tablePivot) {
            $this->tablePivot = BaseApplication::diGet($tablePivot);
            if (!$this->tablePivot) {
                throw new \Exception();
            }
        }

        return $this;
    }

    public function getPivot()
    {
        return $this->tablePivot;
    }

    public function getLeft()
    {
        return $this->tableLeft;
    }

    public function getRight()
    {
        return $this->tableRight;
    }
}
