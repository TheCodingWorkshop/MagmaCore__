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

namespace MagmaCore\Settings\Forms;

use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;
use MagmaCore\Settings\Settings;

class DefaultSettingForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

    /** @var FormBuilderBlueprintInterface $blueprint */
    private FormBuilderBlueprintInterface $blueprint;
    private Settings $settings;

    /**
     * Main class constructor
     *
     * @param FormBuilderBlueprint $blueprint
     * @param Settings $settings
     */
    public function __construct(FormBuilderBlueprint $blueprint, Settings $settings)
    {
        $this->blueprint = $blueprint;
        $this->settings = $settings;
        parent::__construct();
    }

    /**
     * @param string $action
     * @param object|null $dataRepository
     * @param object|null $callingController
     * @return string
     */
    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null): string
    {
        return $this->form(['action' => $action, 'class' => ['uk-form-stacked'], "id" => "tableForm"])
            ->addRepository($dataRepository)
            ->add(
                $this->blueprint->text(
                    'default_role',
                    ['uk-form-large', 'uk-border-bottom', 'uk-form-blank', 'uk-width-1-2'],
                    $this->settings->get('default_role'),
                    false,
                    'Default Role'
                ),
                null,
                $this->blueprint->settings(false, null, false, 'Default Role', true, null, 'This will be the default role assigned to a user when they register for your application.')
            )
            ->add(
                $this->blueprint->submit(
                    'application-settings',
                    ['uk-button', 'uk-button-primary'],
                    'Save'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}
