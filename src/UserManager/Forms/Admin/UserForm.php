<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types=1);

namespace MagmaCore\UserManager\Forms\Admin;

use Exception;
use MagmaCore\DataObjectLayer\DataLayerTrait;
use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;
use MagmaCore\Utility\Yaml;
use MagmaCore\UserManager\Rbac\Role\RoleModel;
use MagmaCore\UserManager\Model\UserRoleModel;

class UserForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

    use DataLayerTrait;
    /** @var FormBuilderBlueprintInterface $blueprint */
    private FormBuilderBlueprintInterface $blueprint;
    private RoleModel $roleModel;
    private UserRoleModel $userRole;

    /**
     * Main class constructor
     *
     * @param FormBuilderBlueprint $blueprint
     * @param RoleModel $roleModel
     */
    public function __construct(FormBuilderBlueprint $blueprint, RoleModel $roleModel, UserRoleModel $userRole)
    {
        $this->blueprint = $blueprint;
        $this->roleModel = $roleModel;
        $this->userRole = $userRole;
        parent::__construct();
    }

    public function getModel(): RoleModel
    {
        return $this->roleModel;
    }

    private function getDefaultRole(int $userID = null): int
    {
        return (int)$this->userRole
        ->getRepo()->findObjectBy(['user_id' => $userID], ['role_id'])->role_id;
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
        return $this->form(['action' => $action, 'class' => ['uk-form-stacked'], "id" => "userForm"])
            ->addRepository($dataRepository)
            ->add($this->blueprint->text('firstname', [], $this->hasValue('firstname')))
            ->add($this->blueprint->text('lastname', [], $this->hasValue('lastname')))
            ->add($this->blueprint->email('email', [], $this->hasValue('email')))
            ->add($this->blueprint->password(
                'password_hash',
                ['uk-form-width-medium'],
                null,
                'new-password',
                false,
                false,
                false,
                ''),
                null,
                $this->blueprint->settings(false, null, true, 'Password', false, null, 'Leaving this field blank will auto generate a random password')
            )
            ->add(
                $this->blueprint->radio('status', [], $this->hasValue('status')),
                $this->blueprint->choices(Yaml::file('controller')['user']['status_choices'], $dataRepository->status ?? 'pending'),
                $this->blueprint->settings(false, null, true, null, true)
            )
            ->add($this->blueprint->select(
                'role_id',
                ['uk-select'],
                'role_id',
                5,
                false,
            ),
                $this->blueprint->choices(
                    array_column($this->roleModel->getRepo()->findBy(['id']), 'id') ?? 'subscriber',
                    /* need to return a list of permission assigned to the role */
                    $this->getDefaultRole($dataRepository->id),
                    $this
                ),
                $this->blueprint->settings(false, null, true, 'Roles', true, 'Select one or more role'))

            ->add($this->blueprint->text(
                'remote_addr',
                ['uk-form-width-small'],
                $this->hasValue('remote_addr'), /* field value */
                true, /* make field disabled */
                'IP Address'),
                null,
                $this->blueprint->settings(false, null, false)
            )
            ->add($this->blueprint->submit(
                $this->hasValue('id') ? 'edit-user' : 'new-user',
                ['uk-button', 'uk-button-secondary', 'uk-form-width-medium'],
                'Save & Continue'
            ),

                null,
                $this->blueprint->settings(false, null, false, null, true)
            )

            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}
