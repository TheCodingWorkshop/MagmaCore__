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

use MagmaCore\Base\Exception\BaseException;
use MagmaCore\Utility\Sanitizer;

class BaseEntity
{

    /** @var array */
    protected array $cleanData;

    /**
     * BaseEntity constructor.
     * Assign the key which is now a property of this object to its array value
     * 
     * @param array $dirtyData
     * @throws BaseException
     */
    public function __construct(array $dirtyData)
    {
        if (empty($dirtyData)) {
            throw new BaseException($dirtyData . 'has return null which means nothing was submitted.');
        }
        if (is_array($dirtyData)) {
            foreach ($this->cleanData($dirtyData) as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Return the sanitize post data back to the main constructor
     * 
     * @param array $dirtyData
     * @return array
     */
    public function cleanData(array $dirtyData) : array
    {
        $cleanData = Sanitizer::clean($dirtyData);
        if ($cleanData) {
            return $cleanData;
        }
    }


}