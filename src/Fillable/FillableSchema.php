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

namespace MagmaCore\Fillable;

use Closure;
use MagmaCore\Fillable\Faker\Faker;
use MagmaCore\Fillable\Exception\FillableNoValueException;
use MagmaCore\Fillable\Exception\FillableOutOfRangeException;
use Throwable;

class FillableSchema
{

    /** @var array $bindValues */
    private array $bindValues = [];
    /** @var array $bindKeys */
    private array $bindKeys = [];
    /** @var Faker $faker - th $faker object */
    private Faker $faker;
    /** @var object $model - the model which to be fill */
    private object $model;

    /**
     * Main constructor class
     *
     * @param Faker $faker
     * @return void
     */
    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Collect the model object for which this fillable will be applicable 
     * to. We can use the object to fetch the database columns
     *
     * @param object $model
     * @return static
     */
    public function table(object $model): static
    {
        if ($model)
            $this->model = $model;
        return $this;
    }

    public function create(int $rows, Closure $callback)
    {
        set_time_limit(31);
        $i = 1;
        do {
            $callback($this);
            sleep(1);
            $i++;
        } while ($i <= $rows);
    }

    /**
     * The value to pass for the fillable column
     *
     * @param mixed $bindValues
     * @return static
     */
    public function fill(mixed $bindValues): static
    {
        if ($bindValues)
            $this->bindValues[] = $bindValues;
        return $this;
    }

    /**
     * Bind the fillable properties to the corresponding column name. We can then
     * build the query to run this fillable
     *
     * @param Closure $callback
     * @return mixed
     */
    public function bind(Closure $callback): mixed
    {
        if ($callback) {
            return $callback($this);
        }
    }

    /**
     * Return an array of the binding columns. Which will bind the values define
     * within the fill method
     *
     * @param array $bindKeys
     * @return static
     */
    public function on(array $bindKeys): static
    {
        if ($bindKeys)
            $this->bindKeys = $bindKeys;
        return $this;
    }


    /**
     * Undocumented function
     *
     * @return boolean
     * @throws Throwable
     */
    public function push(): bool
    {
        $this->throwException();
        try {
            //$i = 0;
            set_time_limit(31);
            $i = 1;
            do {

                $combine = array_combine($this->bindKeys, $this->bindValues);
                $save = $this->model->getRepo()->getEm()->getCrud()->create($combine);
                sleep(1);
                $i++;
                return $save;
            } while ($i <= 20);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Returns the the model object for the current fillables
     *
     * @return object
     */
    public function getModel(): object
    {
        return $this->model;
    }

    /**
     * Returns the faker object
     *
     * @return Faker
     */
    public function faker(): Faker
    {
        return $this->faker;
    }

    /**
     * Returns the integre length of an array
     *
     * @param array $array
     * @return integer
     */
    private function count(array $array): int
    {
        return isset($array) ? count($array) : 0;
    }

    /**
     * Throw an exception if the $bindKeys and $bindValues properties are empty
     * by default the chain can operate without the rows() method as the 
     * class will always generate a single result. If the rows() method is added
     * to the chain then the rows() argument must be greater than 1
     *
     * @return void
     */
    private function throwException(): void
    {
        if (empty($this->bindValues) || empty($this->bindKeys)) {
            throw new FillableNoValueException('No values added to either of the properties');
        }
        if ($keys = $this->count($this->bindKeys) && $values = $this->count($this->bindValues)) {
            if ($keys < $values) {
                throw new FillableOutOfRangeException('Your keys and values are out of range.');
            }
        }
    }
}
