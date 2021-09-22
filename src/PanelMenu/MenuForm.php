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

use MagmaCore\Auth\Model\MenuItemModel;
use MagmaCore\Auth\Model\MenuModel;
use MagmaCore\DataObjectLayer\DataLayerTrait;
use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;
use Exception;

class MenuForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

    use DataLayerTrait;

    /** @var FormBuilderBlueprintInterface $blueprint */
    private FormBuilderBlueprintInterface $blueprint;
    private MenuModel $model;
    private MenuItemModel $menuItem;

    /**
     * Main class constructor
     *
     * @param FormBuilderBlueprint $blueprint
     * @param MenuModel $model
     */
    public function __construct(FormBuilderBlueprint $blueprint, MenuModel $model, MenuItemModel $menuItem)
    {
        $this->blueprint = $blueprint;
        $this->model = $model;
        $this->menuItem = $menuItem;
        parent::__construct();
    }

    /**
     * @return MenuItemModel
     */
    public function getModel(): MenuItemModel
    {
        return $this->menuItem;
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
        $choices = [];
        $defaults = '';
        if ($dataRepository !==null) {
            $defaults = $this->flattenArray($this->menuItem->getRepo()->findBy(['id'], ['item_original_id' => $dataRepository->id, 'item_usable' => 1]));
            $choices = $this->menuItem->getRepo()->findBy(['id'], ['item_original_id' => $dataRepository->id]);
        }
        return $this->form(['action' => $action, 'class' => ['uk-form-stacked'], "id" => "menuForm"])
            ->addRepository($dataRepository)
            ->add($this->blueprint->text('menu_name', [], $this->hasValue('menu_name')))
            ->add($this->blueprint->text('parent_menu', ['uk-width-1-2'], $this->hasValue('parent_menu')))
            ->add($this->blueprint->number('menu_order', ['uk-form-width-small', 'uk-input'], $this->hasValue('menu_order'), false))

            ->add($this->blueprint->text('menu_break_point', ['uk-input'], $this->hasValue('menu_break_point'), false))

            ->add($this->blueprint->textarea('menu_description', ['uk-textarea'], 'menu_description'), $this->hasValue('menu_description'))

            ->add($this->blueprint->select(
                'item_usable[]',
                ['uk-select'],
                'item_usable',
                6,
                true
                ),
                $this->blueprint->choices(
                    array_column($choices, 'id'),
                    $defaults,
                    $this
                ),
                $this->blueprint->settings(false, null, true, 'Menu Items', true, 'Select one one or more menu items')

            )

            ->add(
                $this->blueprint->submit(
                    $this->hasValue('id') ? 'edit-menu' : 'new-menu',
                    ['uk-button', 'uk-button-primary', 'uk-form-width-medium'],
                    $this->hasValue('id') ? 'Update Menu' : 'Add New Menu'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}

