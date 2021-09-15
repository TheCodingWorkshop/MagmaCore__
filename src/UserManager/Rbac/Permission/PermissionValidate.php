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

namespace MagmaCore\UserManager\Rbac\Permission;

use MagmaCore\Collection\Collection;
use MagmaCore\DataObjectLayer\DataRepository\AbstractDataRepositoryValidation;
use MagmaCore\ValidationRule\ValidationRule;

class PermissionValidate extends AbstractDataRepositoryValidation
{

    /** @var array $errors */
    protected array $errors = [];
    /** @var array $dataBag */
    protected array $dataBag = [];
    /** @var ValidationRule $rules */
    protected ValidationRule $rules;

    /** @var string */
    protected const REDIRECT_BACK_TO = '/admin/permission/index';

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
        $this->rules->addObject(PermissionController::class, $this);
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
                'permission_name' => $this->isSet('permission_name', $dataCollection, $dataRepository),
                'permission_description' => $this->isSet('permission_description', $dataCollection, $dataRepository),
                //'permission_group' => $this->isSet('permission_group', $dataCollection, $dataRepository),
                'created_byid' => $this->getCreator($dataCollection)
            ];
            $this->dataBag = [];
        }
        return [
            $newCleanData,
            $this->validatedDataBag($newCleanData),
        ];
    }

    public function validatedDataBag($newCleanData): array
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

    public function fields(): array
    {
        return [];
    }

    /**
     * Returns the redirect path for the validation
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
     * @return void|null
     */
    private function throwWarningIfNoChange(Collection $entityCollection, ?object $dataRepository = null)
    {
        if ($dataRepository !== null) {
            if (
                $entityCollection['permission_name'] === $dataRepository->permission_name &&
                $entityCollection['permission_description'] === $dataRepository->permission_description
            ) {
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
                        'permission_name' => $rules->addRule("required|unique"),
                        'permission_description' => $rules->addRule("required"),
                        default => $this->throwWarningIfNoChange($entityCollection, $dataRepository)
                    };
                }
            }
        );
    }
}
