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

class FillableSchema
{

    /** @var array $bindValues */
    private array $bindValues = [];
    private array $bindKeys = [];
    private int|null $record = 0;

    /** @var object $model - the model which to be fill */
    private object $model;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Collect the model object for which this fillable will be applicable 
     * to. We can use the object to fetch the atabase columns
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

    public function rows(int $record = 0): static
    {
        if ($record)
            $this->record = $record;
        return $this;
    }

    /**
     * Collect the value from the fill method and store it within an class array
     * property. This will allow us to collect all the value ready for binding
     * to the database table column name
     *
     * @param mixed $value
     * @return static
     */
    public function fill(mixed $bindValues): static
    {
        if ($bindValues)
            $this->bindValues[] = $bindValues;
        return $this;
    }

    /**
     * Undocumented function
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

    public function push()
    {
        if (empty($this->bindValues) || empty($this->bindKeys)) {
            throw new FillableNoValueException('No values added to either of the properties');
        }
        if (isset($this->record) && $this->record < 0) {
            throw new FillableNoValueException('If you added the (rows()) method to the chain. Then you must specify a value greater than zero (0)');
            unset($this->record);
        }
        $combine = array_combine($this->bindKeys, $this->bindValues);
        if ($this->record > 1) {
            for ($i = 0; $i < $this->record; $i++) {
                $this->model
                    ->getRepo()
                    ->getEm()
                    ->getCrud()
                    ->create($combine);
            }
            die;
        }
    }


    /**
     * Bind the fillable propertie to the corresponding column name. We can then 
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
}
