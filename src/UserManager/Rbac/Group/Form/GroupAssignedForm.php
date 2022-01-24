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

namespace MagmaCore\UserManager\Rbac\Group\Form;

use Exception;
use MagmaCore\DataObjectLayer\DataLayerTrait;
use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;
use MagmaCore\UserManager\Rbac\Role\RoleModel;
use MagmaCore\UserManager\Rbac\Group\Model\GroupRoleModel;

class GroupAssignedForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

    use DataLayerTrait;

    /** @var FormBuilderBlueprintInterface $blueprint */
    private FormBuilderBlueprintInterface $blueprint;
    private RoleModel $roles;
    private GroupRoleModel $groupRole;

    /**
     * Main class constructor
     *
     * @param FormBuilderBlueprint $blueprint
     * @param RoleModel $roles
     * @param GroupRoleModel $rolePerm
     */
    public function __construct(FormBuilderBlueprint $blueprint, RoleModel $roles, GroupRoleModel $groupRole)
    {
        $this->blueprint = $blueprint;
        $this->roles = $roles;
        $this->groupRole = $groupRole;
        parent::__construct();
    }

    /**
     * @return RoleModel
     */
    public function getModel(): RoleModel
    {
        return $this->roles;
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
        $defaults = $this->flattenArray($this->groupRole->getRepo()->findBy(['role_id'], ['group_id' => $callingController->thisRouteID()]));
        return $this->form(['action' => $action, 'class' => ['uk-form-stacked'], "id" => "group_assigned_form"])
            ->addRepository($dataRepository)
            ->add($this->blueprint->text(
                'group_name',
                [],
                $this->hasValue('group_name'),
                true),
                NULL,
                $this->blueprint->settings(false, null, true, null, true, null, 'Group name cannot be changed here?'))
            ->add($this->blueprint->hidden('role_id', $dataRepository->id), NULL, $this->blueprint->settings(false, null, false, null, true, null))
            ->add($this->blueprint->select(
                'role_id[]',
                ['uk-select'],
                'role_id',
                10,
                true,
            ),
                $this->blueprint->choices(
                    array_column($this->roles->getRepo()->findBy(['id']), 'id'),
                    /* need to return a list of permission assigned to the role */
                    $defaults,
                    $this
                ),
                $this->blueprint->settings(false, null, true, 'Roles', true, 'Select one one or more roles'))

            ->add(
                $this->blueprint->submit(
                    'assigned-group',
                    ['uk-button', 'uk-button-primary', 'uk-form-width-medium'],
                    'Save'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}

