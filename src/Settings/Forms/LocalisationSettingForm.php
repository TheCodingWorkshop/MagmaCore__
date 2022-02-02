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

class LocalisationSettingForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

    /** @var FormBuilderBlueprintInterface $blueprint */
    private FormBuilderBlueprintInterface $blueprint;

    /**
     * Main class constructor
     *
     * @param FormBuilderBlueprint $blueprint
     * @return void
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
     */
    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null): string
    {
        return $this->form(['action' => $action, 'enctype' => 'multipart/form-data', 'class' => ['uk-form-stacked'], "id" => "tableForm"])
            ->addRepository($dataRepository)
            ->add(
                $this->blueprint->text('file_name', ['uk-form-blank', 'uk-border-bottom'], $this->hasValue('file_name'), false, 'Filename'),
                NULL,
                $this->blueprint->settings(false, null, false, null, true)

            )
            ->add(
                $this->blueprint->text('locale', ['uk-form-blank', 'uk-border-bottom'], $this->hasValue('locale'), false, 'Locale'),
                NULL,
                $this->blueprint->settings(false, null, false, null, true)

            )
            ->add(
                $this->blueprint->upload('file_path', ['uk-button', 'uk-button-small', 'uk-button-default'], (string)$this->hasValue('file_path'), true),
                '<span class="ion-28"><ion-icon style="margin-top:10px;" name="cloud-upload"></ion-icon></span>',
                $this->blueprint->settings(false, null, false, null, true, '', 'Upload your locale .yml file')
            )
            ->add(
                $this->blueprint->submit(
                    'localisation-setting',
                    ['uk-button', 'uk-button-primary'],
                    'Save'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}
