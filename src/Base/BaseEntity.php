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

namespace MagmaCore\Base;

use MagmaCore\Utility\Sanitizer;
use MagmaCore\Collection\Collection;
use MagmaCore\Base\Exception\BaseException;

class BaseEntity
{

    /** @var array */
    protected array $cleanData;
    /** @var array */
    protected array $dirtyData;
    /** */
    protected object|null $dataSchemaObject;

    /**
     * BaseEntity constructor.
     * Assign the key which is now a property of this object to its array value
     * 
     * @param array $dirtyData
     * @throws BaseException
     */
    public function __construct()
    {}

    /**
     * Accept raw unthreated data and prepare for sanitization
     *
     * @param array $dirtyData
     * @return self
     */
    public function wash(array $dirtyData): static
    {
        if (empty($dirtyData)) {
            throw new BaseException($dirtyData . 'has return null which means nothing was submitted.');
        }
        $this->dirtyData = $dirtyData;
        return $this;
    }

    /**
     * Ensure the data is of the correct data type before passing it through 
     * the sanitization class
     *
     * @return static
     */
    public function rinse(): static
    {
        if (!is_array($this->dirtyData)) {
            throw new BaseException(getType($this->dirtyData) . ' is an invalid type for this object. Please return an array of submitted data.');
        }
        $this->cleanData = Sanitizer::clean($this->dirtyData);
        return $this;
    }

    /**
     * Return the clean data as a new collection object. Also allowing 
     * accessing the individual submitted data propert. By simple 
     * calling the $this->(field_name)
     *
     * @return object
     */
    public function dry(): object
    {
        foreach ($this->cleanData as $key => $value) {
            $this->$key = $value;
        }
        return new Collection($this->cleanData);
    }
}
