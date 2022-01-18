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

namespace MagmaCore\PanelMenu;

use MagmaCore\PanelMenu\MenuController;
use MagmaCore\Collection\Collection;
use MagmaCore\DataObjectLayer\DataRepository\AbstractDataRepositoryValidation;
use MagmaCore\ValidationRule\ValidationRule;

class MenuValidate extends AbstractDataRepositoryValidation
{

    /** @var array $errors */
    protected array $errors = [];
    /** @var array $dataBag */
    protected array $dataBag = [];
    /** @var ValidationRule $rules */
    protected ValidationRule $rules;

    /** @var string */
    protected const REDIRECT_BACK_TO = '/admin/menu/index';

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
        $this->rules->addObject(MenuController::class, $this);
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
        $dataCollection = $this->mergeWithFields((array)$entityCollection->all());
        $newCleanData = [];
        if (null !== $dataCollection) {
            $newCleanData = [
                'menu_name' => $this->isSet('menu_name', $dataCollection, $dataRepository),
                'menu_description' => $this->isSet('menu_description', $dataCollection, $dataRepository),
                'parent_menu' => $this->isSet('parent_menu', $dataCollection, $dataRepository),
                'menu_break_point' => $this->isSet('menu_break_point', $dataCollection, $dataRepository),
                'menu_icon' => $this->isSet('menu_icon', $dataCollection, $dataRepository),
                'menu_order' => $this->isSet('menu_order', $dataCollection, $dataRepository),
                'created_byid' => $this->getCreator($dataCollection)
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
            if (
                $entityCollection['menu_name'] === $dataRepository->role_name &&
                $entityCollection['menu_description'] === $dataRepository->role_description
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
                        'menu_name' => $rules->addRule("required|unique"),
                        'menu_description' => $rules->addRule("required"),
                        default => $this->throwWarningIfNoChange($entityCollection, $dataRepository)
                    };
                }
            }
        );
    }
}

