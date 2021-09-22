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

class DatetimeSettingForm extends ClientFormBuilder implements ClientFormBuilderInterface
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
        $this->blueprint = $blueprint;#
        parent::__construct();
    }

    private function getDate(string $format): string
    {
        return date($format) . ' (' . $format . ')';
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
                $this->blueprint->radio(
                    'date_format',
                    [],
                    $this->hasValue('date_format'),
                ),
                $this->blueprint->choices(
                    [
                        'j F Y' => $this->getDate('j F Y'),
                        'Y-m-d' => $this->getDate('Y-m-d'),
                        'm/d/Y' => $this->getDate('m/d/Y'),
                        'd/m/Y' => $this->getDate('d/m/Y')
                    ],
                    'j F Y'
                ),
                $this->blueprint->settings(false, null, true, null, true)
            )
            ->add(
                $this->blueprint->submit(
                    $this->hasValue('settings_id') ? 'edit-settings' : 'new-settings',
                    ['uk-button', 'uk-button-primary'],
                    'Save'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}
