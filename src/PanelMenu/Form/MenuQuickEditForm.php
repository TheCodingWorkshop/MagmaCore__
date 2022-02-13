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

namespace MagmaCore\PanelMenu\Form;

use Exception;
use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\DataObjectLayer\DataLayerTrait;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;

class MenuQuickEditForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

    use DataLayerTrait;

    /** @var FormBuilderBlueprintInterface $blueprint */
    private FormBuilderBlueprintInterface $blueprint;

    /**
     * Main class constructor
     *
     * @param FormBuilderBlueprint $blueprint
     * @param MenuModel $model
     */
    public function __construct(FormBuilderBlueprint $blueprint)
    {
        $this->blueprint = $blueprint;
        parent::__construct();
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

        return $this->form(['action' => $action, 'class' => ['uk-form-stacked'], "id" => "menuForm"])
            ->addRepository($dataRepository)
            ->add($this->blueprint->number('menu_order', ['uk-form-width-small', 'uk-input'], $this->hasValue('menu_order'), false))

            ->add($this->blueprint->text('menu_break_point', ['uk-input'], $this->hasValue('menu_break_point'), false))

            ->add($this->blueprint->text(
                'menu_icon', 
                ['uk-form-1-2', 'uk-input'], 
                $this->hasValue('menu_icon'), false
            ),
            null,
            $this->blueprint->settings(
                false, 
                null, 
                true, 
                '', 
                false, 
                null, 
                '<a class="uk-text-link" target="_blank" href="https://ionic.io/ionicons">Get more icons</a>')
            )
            ->add(
                $this->blueprint->submit(
                    'index-menu',
                    ['uk-button', 'uk-button-primary', 'uk-form-width-medium'],
                    'Quick Save'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>'], true, false);
    }
}

