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

use Exception;
use MagmaCore\Base\BaseApplication;

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

    public function setKey(mixed $key): static
    {
        $this->ukey = $key;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param Callable $callback
     * @return string
     * @throws Exception
     */
    public function setConstraints(Callable $callback): string
    {
        if (!is_callable($callback)) {
            throw new Exception();
        }

        $out = $this->addKeys();
        $out .= $callback($this);

        return $out;

    }

    /**
     * Undocumented function
     *
     * @return array|string
     */
    public function addKeys(): array|string
    {
        $key = '';
        if (isset($this->primaryKey)) {
            if (!empty($this->primaryKey)) {
                $key .= "PRIMARY KEY (`{$this->primaryKey}`)," . PHP_EOL;
            }    
        }
        if (isset($this->uniqueKey)) {
            if (is_array($this->uniqueKey) && count($this->uniqueKey) > 0) {
                $uniqueKey = (array) $this->uniqueKey;
                foreach ($uniqueKey as $unique) {
                    $key .= "UNIQUE KEY `{$unique}` (`{$unique}`)" . "," . PHP_EOL;
                }
                $key = substr_replace($key, '', -3);
            } else {
                $key .= "UNIQUE KEY `{$this->uniqueKey}` (`{$this->uniqueKey}`), ";
            }
    
        }
        if (isset($this->ukey)) {
            if (is_array($this->ukey) && count($this->ukey) > 0) {
                $ukey = (array) $this->ukey;
                foreach ($ukey as $_ukey) {
                    $key .= "KEY `{$_ukey}` (`{$_ukey}`)" . "," . PHP_EOL;
                }
                $key = substr_replace($key, '', -3);
            } else {
                $key .= "KEY `{$this->ukey}` (`{$this->ukey}`), ";
            }
    
        }
        return $key;
    }

    /**
     * Undocumented function
     *
     * @param mixed $foreignKey
     * @return static
     */
    public function foreignKey(mixed $foreignKey): static
    {
        $this->foreignKey = $foreignKey;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param mixed $on
     * @return static
     */
    public function on(mixed $on): static
    {
        $this->on = $on;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param mixed $reference
     * @return static
     */
    public function reference(mixed $reference): static
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

    public function addModel(string $model): static
    {
        if (!empty($model)) {
            $model = BaseApplication::diGet($model);
            if ($model) {
                $this->model = $model;
            }
        }
        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function add(): string
    {
        $out = '';
        //if (isset($this->uniqueKey)) {
             $out .= ',';
        // } 
        if (isset($this->foreignKey)) {
            $out .= " FOREIGN KEY (`{$this->foreignKey}`)";
        }
        if (isset($this->on)) {
            $out .= " REFERENCES `{$this->on}`";
        }
        if (isset($this->reference)) {
            $out .= " (`{$this->reference}`)";
        }

        $out .= (isset($this->deleteCascade) && $this->deleteCascade === true) ? 'ON DELETE CASCADE' : '';
        $out .= (isset($this->updateCascade) && $this->updateCascade === true) ? ' ON UPDATE CASCADE' : '';
        //$out .= ',';
        //$out = substr($out, 0, -3);
        //$out .= PHP_EOL;

        return $out;

    }

}
