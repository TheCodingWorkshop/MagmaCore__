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

use ArrayIterator;
use MagmaCore\Collection\CollectionTrait;
use MagmaCore\Collection\CollectionInterface;

class Collection implements CollectionInterface
{

    use CollectionTrait;

    /** @var array - collection items */
    protected mixed $items = [];


    public function __construct(mixed $items = [])
    {
        $this->items = (array)$items;
    }

    /**
     * Returns all the items within the collection
     *
     * @return void
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Checks whether a given key exists within the collection
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * Returns all the keys of the collection items
     *
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->items);
    }

    /**
     * Run a map over each items
     *
     * @param Callable $callback
     * @return static
     */
    public function map(callable $callback): static
    {
        $items = array_map($callback, $this->items, $this->keys());
        return new static(array_combine($this->keys(), $items));
    }

    public function avg()
    {
        if ($size = $this->size()) {
            $array = array_filter($this->items);
            $avg = array_sum($array) / $size;
            return $avg;
        }
    }

    /**
     * Calculates the sum of values within the specified array
     *
     * @param array $array
     * @return static
     */
    public function sum(): static
    {
        return new static(array_sum($this->items));
    }

    public function min()
    {
    }

    public function max()
    {
    }

    /**
     * Create an collection with the specified ranges
     *
     * @param mixed $from
     * @param mixed $to
     * @return static
     */
    public function  range($from, $to): static
    {
        return new static(range($from, $to));
    }

    /**
     * Merge the collection with the given argument 
     *
     * @param mixed $items
     * @return static
     */
    public function merge(mixed $items): static
    {
        return new static($this->items, $items);
    }

    /**
     * Recursively merge the collection with the given argument
     *
     * @param mixed $items
     * @return static
     */
    public function mergeRecursive(mixed $items): static
    {
        return new static(array_merge_recursive($this->items, $items));
    }

    /**
     * Pop an element off the end of the item collection
     *
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * Push elements on the end of the collection items
     *
     * @param mixed ...$values
     * @return self
     */
    public function push(...$values): self
    {
        array_push($this->items, $values);
        return $this;
    }

    /**
     * Returns the items collection in reverse order
     *
     * @return static
     */
    public function reverse(): static
    {
        return new static(array_reverse($this->items, true));
    }

    /**
     * Shift the first element of the collection items
     *
     * @return mixed
     */
    public function shift(): mixed
    {
        return array_shift($this->items);
    }

    /**
     * Extract a slice of the collection items
     *
     * @param [type] $offset
     * @param [type] $length
     * @return static
     */
    public function slice($offset, $length = null): static
    {
        return new static(array_slice($this->items, $offset, $length, true));
    }

    /**
     * Returns the values of the collection items
     *
     * @return static
     */
    public function values(): static
    {
        return new static(array_values($this->items));
    }

    /**
     * Count the number of items within the collection items
     *
     * @return integer
     */
    public function size(): int
    {
        return count($this->items);
    }

    /**
     * Add an item to the collection
     *
     * @param mixed $item
     * @return self
     */
    public function add(mixed $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * Remove the item from the collection
     *
     * @param string $key
     * @return void
     */
    public function remove(string $key): void
    {
        unset($this->items[$key]);
    }

    /**
     * Removes duplicate entry from the collection items
     *
     * @return static
     */
    public function unique(): static
    {
        return new static(array_unique($this->items));
    }

    /**
     * Returns the items in the collection which is not within the specified index array
     *
     * @param mixed $items
     * @return static
     */
    public function diff(mixed $items): static
    {
        return new static(array_diff($this->items, $items));
    }

    /**
     * Returns the items in the collection which is not within the the specified associative array
     *
     * @param mixed $items
     * @return static
     */
    public function diffAssoc(mixed $items): static
    {
        return new static(array_diff_assoc($this->items, $items));
    }

    /**
     * Returns the items in the collection whose keys and values is not within the 
     * specified associative array, using the callback
     *
     * @param mixed $items
     * @param Callable $callback
     * @return static
     */
    public function diffAssocUsing(mixed $items, callable $callback): static
    {
        return new static(array_diff_uassoc($this->items, $items, $callback));
    }

    /**
     * Returns the items in the collection whose keys in not within the specified 
     * index array
     *
     * @param mixed $items
     * @return static
     */
    public function diffKeys(mixed $items): static
    {
        return new static(array_diff_key($this->items, $items));
    }

    /**
     * Returns the items in the collection whose keys in not within the specified 
     * index array, using the callback
     *
     * @param mixed $items
     * @param Callable $callback
     * @return static
     */
    public function diffKeysUsing(mixed $items, callable $callback): static
    {
        return new static(array_diff_ukey($this->items, $items, $callback));
    }

    /**
     * Run a filter over each of the collection item
     * 
     * @param Callable $callback
     * @return static
     */
    public function filter(callable $callback = null): static
    {
        if ($callback) {
            return new static($this->where($this->items, $callback));
        }
        return new static(array_filter($this->items));
    }

    /**
     * Get the first item from the collection passing the given truth test.
     *
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public function first(callable|null $callback = null, $default = null)
    {
        return $this->first($this->items, $callback, $default);
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->map(function ($value) {
            return $value;
        })->all();
    }

    public function offsetExists(mixed $key)
    {
        return isset($this->items[$key]);
    }

    public function offsetGet(mixed $key)
    {
        return $this->items[$key];
    }

    public function offsetSet(mixed $key, mixed $value)
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    public function offsetUnset(mixed $key)
    {
        unset($this->items[$key]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Aliase of $this->size method
     *
     * @return void
     */
    public function count(): int
    {
        return $this->size();
    }
}
