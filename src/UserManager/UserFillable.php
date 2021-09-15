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

namespace MagmaCore\UserManager;

use MagmaCore\Fillable\FillableSchema;
use MagmaCore\Fillable\FillableSchemaInterface;
use JetBrains\PhpStorm\Pure;

class UserFillable extends FillableSchema implements FillableSchemaInterface
{

    /** @var FillableSchema $fillable */
    private FillableSchema $fillable;
    /** @var UserModel $userModel ; */
    private UserModel $userModel;

    /**
     * Main constructor class. Any typed hinted dependencies will be autowired. As this
     * class can be inserted inside a dependency container
     *
     * @param FillableSchema $fillable
     * @param UserModel $userModel
     */
    public function __construct(FillableSchema $fillable, UserModel $userModel)
    {
        $this->fillable = $fillable;
        $this->userModel = $userModel;
    }

    /**
     *
     * @return bool
     */
    public function createFillable(): bool
    {
        $this->fillable->create(1, function ($fillable, $rows) {
            return $fillable
                ->table($this->userModel)
                ->fill($fillable->faker()->firstname)
                ->fill($fillable->faker()->lastname)
                ->fill($fillable->faker()->email)
                ->fill($fillable->faker()->status('pending'))
                ->fill($fillable->faker()->fakePassword)
                ->fill(1) /* this represent the created_byid field which is an integre user id of 1 */
                ->fill($fillable->faker()->remoteIP)
                ->bind(function ($fillable) use ($rows) {
                    return $fillable
                        ->on($fillable->getModel()->getFillables(UserModel::class))
                        ->push($rows);
                });

        });

        return false;

    }
}
