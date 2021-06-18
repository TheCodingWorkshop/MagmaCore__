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

use MagmaCore\Fillable\Faker\Faker;

class FillableBlueprint implements FillableBlueprintInterface
{

    private Faker $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;  
    }

    public function faker(): object
    {
        return $this->faker;
    }
}