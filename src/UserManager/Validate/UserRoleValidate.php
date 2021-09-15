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

namespace MagmaCore\UserManager\Validate;

use MagmaCore\UserManager\Controller\UserRoleController;
use MagmaCore\Collection\Collection;
use MagmaCore\DataObjectLayer\DataRepository\AbstractDataRepositoryValidation;
use MagmaCore\ValidationRule\ValidationRule;

class UserRoleValidate extends AbstractDataRepositoryValidation
{

    /** @var array $errors */
    protected array $errors = [];
    /** @var array $dataBag */
    protected array $dataBag = [];
    /** @var ValidationRule $rules */
    protected ValidationRule $rules;

    /** @var string */
    protected const REDIRECT_BACK_TO = '/admin/role/index';

    /**
     * Main class constructor. Uses the ValidateRule class has a dependency
     * We are also declaring the $this->rules->addObject() method which takes two
     * argument. First is a qualified namespace of the controller class which
     * calls this validation class and $this keyword which represents this
     * current object. This way we can actually get access to the controller
     * class throw the ValidationRule object
     *
     * @param ValidationRule $rules
     * @return void
     */
    public function __construct(ValidationRule $rules)
    {
        $this->rules = $rules;
        $this->rules->addObject(UserRoleController::class, $this);
    }

    /**
     * Validate the data before persisting to the database ensure
     * the entity return valid email and password fields
     *
     * @param Collection $entityCollection - the incoming data
     * @param object|null $dataRepository - the repository for the entity
     * @return array
     */
    public function validateBeforePersist(Collection $entityCollection, ?object $dataRepository = null): array
    {
        $this->validate($entityCollection, $dataRepository);
        $dataCollection = $this->mergeWithFields($entityCollection->all());
        $newCleanData = [];
        if (null !== $dataCollection) {
            $newCleanData = [
                'role_id' => $this->isSet('role_id', $dataCollection, $dataRepository),
            ];
            /* We can return an empty dataBag even if we have nothing to send */
            $this->dataBag = [];
        }
        return [
            $newCleanData,
            $this->validatedDataBag($newCleanData),
        ];
    }

    /**
     * Returns the validated data which will be dispatched to any listeners
     *
     * @param array $newCleanData
     * @return array
     */
    public function validatedDataBag(array $newCleanData): array
    {
        return array_merge($newCleanData, $this->dataBag);
    }

    /**
     * Returns the error if any was generated
     *
     * @return array
     */
    public function getErrors(): array
    {
        return [];
    }

    /**
     * Add additional fields
     *
     * @return array
     */
    public function fields(): array
    {
        return [];
    }

    /**
     * Provides a redirect path for the validation class to use.
     *
     * @return string
     */
    public function validationRedirect(): string
    {
        return sprintf('%s', self::REDIRECT_BACK_TO);
    }

    /**
     * Return a feedback if the save button was click but no data was change or modified
     * from the form
     *
     * @param Collection $entityCollection
     * @param object|null $dataRepository
     * @return null
     */
    private function throwWarningIfNoChange(Collection $entityCollection, ?object $dataRepository = null)
    {
        if ($dataRepository !== null) {
            if ($entityCollection['role_id'] === $dataRepository->role_id) {
                if ($controller = $this->rules->getController()) {
                    if ($controller->error) {
                        $controller->error
                            ->addError(['no_change' => 'No Changes'], $controller)
                            ->dispatchError(self::REDIRECT_BACK_TO);
                    }
                }
            }

        }
        return null;
    }

    /**
     * Validate the data collection fields
     *
     * @param Collection $entityCollection
     * @param Object|null $dataRepository
     * @return void
     */
    public function validate(Collection $entityCollection, ?object $dataRepository = null): void
    {
        $this->doValidation(
            $entityCollection,
            $dataRepository,
            function ($key, $value, $entityCollection, $dataRepository) {
                if ($rules = $this->rules) {
                    return match ($key) {
                        'role_id' => $rules->addRule("required"),
                        default => $this->throwWarningIfNoChange($entityCollection, $dataRepository)
                    };
                }
            }
        );
    }
}

