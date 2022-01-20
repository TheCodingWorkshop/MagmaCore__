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

class GeneralSettingForm extends ClientFormBuilder implements ClientFormBuilderInterface
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
                    'site_name',
                    ['uk-form-large', 'uk-border-bottom', 'uk-form-blank'],
                    $this->settings->get('site_name'),
                    false,
                    'Site Name...'
                ),
                null,
                $this->blueprint->settings(false, null, false, 'Name', true, null, 'This represent your site name.')
            )
            ->add(
                $this->blueprint->email(
                    'site_email',
                    ['uk-width-1-2', 'uk-form-large', 'uk-form-blank', 'uk-border-bottom'],
                    $this->settings->get('site_email'),
                    true,
                    false,
                    'Email Address'
                ),
                null,
                $this->blueprint->settings(
                    false,
                    null,
                    false,
                    'Email',
                    true,
                    null,
                    'This address is used for admin purposes. If you change this, we will send you an email at your new address to confirm it. The new address will not become active until confirmed.'
                )
            )
            ->add(
                $this->blueprint->text(
                    'site_url',
                    ['uk-width-1-2', 'uk-form-large', 'uk-form-blank', 'uk-border-bottom'],
                    $this->settings->get('site_url'),
                    false,
                    'Application Address'
                ),
                null,
                $this->blueprint->settings(
                    false,
                    null,
                    false,
                    'Application Address',
                    true,
                    null,
                    'This is the domain where your application live.'
                )
            )
            ->add(
                $this->blueprint->text(
                    'site_tagline',
                    ['uk-form-large', 'uk-form-blank', 'uk-border-bottom'],
                    $this->settings->get('site_tagline'),
                    false,
                    'Tagline'
                ),
                null,
                $this->blueprint->settings(false, null, false, 'Tagline', true, null, 'In a few words, explain what this site is about.')
            )
            ->add(
                $this->blueprint->text(
                    'site_keywords',
                    ['uk-form-large', 'uk-form-blank', 'uk-border-bottom'],
                    $this->settings->get('site_keywords'),
                    false,
                    'Keywords'
                ),
                null,
                $this->blueprint->settings(false, null, false, 'Keywords', true, null, 'Add your global site keywords here. These will be displayed across your application meta keywords tag.')
            )
            ->add(
                $this->blueprint->textarea(
                    'site_description',
                    ['uk-textarea', 'uk-form-blank', 'uk-border-bottom'],
                    'site_description'
                ),
                $this->settings->get('site_description'),
                $this->blueprint->settings(false, null, false, null, true, null, 'Add some brief description about your application.')
            )
            ->add(
                $this->blueprint->select(
                    'menu_icon',
                    ['uk-select', 'uk-form-width-small'],
                    'id',
                    $this->settings->get('menu_icon')
                ),
                $this->blueprint->choices(['on', 'off'], $this->settings->get('menu_icon')),
                $this->blueprint->settings(false, null, false, null, true, null, 'Enable/Disable menu icons.')
            )

            ->add(
                $this->blueprint->submit(
                    'general-setting',
                    ['uk-button', 'uk-button-primary'],
                    'Save'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}
