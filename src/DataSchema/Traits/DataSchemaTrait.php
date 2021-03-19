<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

namespace MagmaCore\DataSchema\Traits;

trait DataSchemaTrait
{

    /**
     * Undocumented function
     *
     * @param string $pirmaryKey
     * @return static
     */
    public function addPrimaryKey(string $pirmaryKey): static
    {
        $this->primaryKey = $pirmaryKey;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $uniqueKey
     * @return static
     */
    public function setUniqueKey(mixed $uniqueKey): static
    {
        $this->uniqueKey = $uniqueKey;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $identifier
     * @param Callable $callback
     * @return static
     */
    public function setConstraintKeys(callable $callback): static
    {
        if (is_callable($callback)) {
            call_user_func_array(
                $callback,
                []
            );
        }
        return $this;

    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function addKeys()
    {
        $key = '';
        if (!empty($this->primaryKey)) {
            $key .= "PRIMARY KEY (`{$this->primaryKey}`),";
        }
        if (is_array($this->uniqueKey) && count($this->uniqueKey) > 0) {
            $uniqueKey = (array) $this->uniqueKey;
            foreach ($uniqueKey as $unique) {
                $key .= "UNIQUE KEY `{$unique}` (`{$unique}`),";
            }
        } else {
            $key .= "UNIQUE KEY `{$this->uniqueKey}` (`{$this->uniqueKey}`),";
        }

        $key .= $this->add();

        return $key;
    }

    /**
     * Undocumented function
     *
     * @param string $foreignKey
     * @return static
     */
    public function foreignKey(string $foreignKey): static
    {
        $this->foreignKey = $foreignKey;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $on
     * @return static
     */
    public function on(string $on): static
    {
        $this->on = $on;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $reference
     * @return static
     */
    public function reference(string $reference): static
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param mixed $delete
     * @param mixed $update
     * @return static
     */
    public function cascade(mixed $delete = false, mixed $update = false): static
    {
        if ($delete) {
            $this->deleteCascade = $delete;
        }
        if ($update) {
            $this->updateCascade = $update;
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function add(): string
    {
        $out = '';
        if ($this->foreignKey) {
            $out .= " FOREIGN KEY (`{$this->foreignKey}`)";
        }

        if ($this->on) {
            $out .= " REFERENCES `{$this->on}`";
        }

        if ($this->reference) {
            $out .= "(`{$this->reference}`)\n";
        }

        $out .= (isset($this->deleteCascade) && $this->deleteCascade === true) ? ' ON DELETE CASCADE' : '';
        $out .= (isset($this->updateCascade) && $this->updateCascade === true) ? ' ON UPDATE CASCADE' : '';

        return $out;

    }

}
