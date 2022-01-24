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

namespace MagmaCore\UserManager\Rbac\Group;

use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;
use MagmaCore\Utility\Utilities;
use Exception;

class GroupForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

    /** @var FormBuilderBlueprintInterface $blueprint */
    private FormBuilderBlueprintInterface $blueprint;
    private GroupModel $model;

    /**
     * Main class constructor
     *
     * @param FormBuilderBlueprint $blueprint
     * @param GroupModel $model
     */
    public function __construct(FormBuilderBlueprint $blueprint, GroupModel $model)
    {
        $this->blueprint = $blueprint;
        $this->model = $model;
        parent::__construct();
    }

    /**
     * @return PermissionModel
     */
    public function getModel(): GroupModel
    {
        return $this->model;
    }

    /**
     * @param string $action
     * @param object|null $dataRepository
     * @param object|null $callingController
     * @return string
     * @throws Exception
     */
    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null): string
    {
        return $this->form(['action' => $action, 'class' => ['uk-form-stacked'], "id" => "groupForm"])
            ->addRepository($dataRepository)
            ->add($this->blueprint->text('group_name', [], $this->hasValue('group_name')))
            ->add($this->blueprint->textarea('group_description', ['uk-textarea'], 'group_name'), $this->hasValue('group_description'))
            ->add(
                $this->blueprint->submit(
                    $this->hasValue('id') ? 'edit-group' : 'new-group',
                    ['uk-button', 'uk-button-primary', 'uk-form-width-medium'],
                    'Save'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }


}
