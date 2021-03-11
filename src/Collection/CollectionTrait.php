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

namespace MagmaCore\Collection;

use Closure;
use MagmaCore\Collection\Collection;
use MagmaCore\Collection\CollectionProxy;
use MagmaCore\Base\Exception\BaseException;

trait CollectionTrait
{

    /** @var array - support collection methods */
    protected static array $proxies = [
        'all',
        'avg',
        'sum',
        'median',
        'size',
        'flat',
        'map',
        'filter',
        'diff',
        'min',
        'max',
        'range',
        'sort',
        'unique',
        'keys',
        'values',
        'remove',
        'get',
        'has',
        'walk',
        'slice',
        'pluck',
        'add',
        'pop',
        'shift',
        'empty'
    ];

    public function __get($key) 
    {
        if (!in_array($key, self::$proxies)) {
            throw new BaseException("Property [{$key}] does not exist on this collection instance.");
        }
        return new CollectionProxy($this, $key);
    }

    /**
     * add a method to the array of proxies
     *
     * @param string $method
     * @return void
     */
    public function proxy(string $method) : void
    {
        static::$proxies[] = $method;
    }

    /**
     * Cast $items
     *
     * @param mixed $items
     * @return array
     */
    public function arrayableItems($items): array
    {
        return (array)$items;
    }

    /**
     * Checks whether the input array is of an associative array type
     *
     * @param array $inputArray
     * @return boolean
     */
    public static function isAssoc(array $inputArray)
    {
        $keys = array_keys($inputArray);
        return array_keys($keys) !== $keys;
    }   

    /**
     * Return a instance of the collection object
     *
     * @return Collection
     */
    public function collect(): Collection
    {
        return new Collection($this->all());
    }

    /**
     * Get the collection of items as a plain array
     *
     * @return array
     */
    public function toArrays(): array
    {
        return $this->map(function($value){
            //return $value  ? $value->toArray() : $value;
        });
    }

/**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function value($value, ...$args)
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }


/**
     * Return the first element in an array passing a given truth test.
     *
     * @param  iterable  $array
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public function first($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return $this->value($default);
            }

            foreach ($array as $item) {
                return $item;
            }
        }

        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return $this->value($default);
    }

    /**
     * Filter the array using the given callback
     *
     * @param array $input
     * @param Callable $callback
     * @return void
     */
    public function where(array $input, Callable $callback)
    {
        return array_filter($input, $callback, ARRAY_FILTER_USE_BOTH);
    }

    public function flatten()
    {
    }

    public function flattenRecursively()
    {
    }
}
