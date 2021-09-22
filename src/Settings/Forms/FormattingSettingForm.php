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

class FormattingSettingForm extends ClientFormBuilder implements ClientFormBuilderInterface
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

    private function getDate(string $format): string
    {
        return date($format);
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
                    'timezone',
                    ['uk-form-large', 'uk-width-1-2', 'uk-form-blank', 'uk-border-bottom'],
                    $this->settings->get('locale'),
                    false,
                    'Site Name'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true, null, 'Choose your default locale when using this application.')
            )
            ->add(
                $this->blueprint->text(
                    'timezone',
                    ['uk-form-large', 'uk-form-blank', 'uk-border-bottom'],
                    $this->settings->get('timezone'),
                    false,
                    'Site Name'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true, null, 'Choose either a city in the same time zone as you or a UTC (Coordinated Universal Time) time offset.')
            )
            ->add(
                $this->blueprint->select(
                    'week_starts_on',
                    ['uk-select', 'uk-form-large', 'uk-width-1-2', 'uk-form-blank', 'uk-border-bottom'],
                    'week_starts_on',
                ),
                $this->blueprint->choices(
                    ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
                    $this->settings->get('week_starts_on')
                ),
                $this->blueprint->settings(false, null, false, null, true, null, 'Choose what day the week starts on in your region.')
            )
            ->add(
                $this->blueprint->select(
                    'date_format',
                    ['uk-select', 'uk-form-large', 'uk-width-1-2', 'uk-form-blank', 'uk-border-bottom'],
                    'date_format',
                ),
                $this->blueprint->choices(
                    [
                        'j F Y' => $this->getDate('j F Y'),
                        'Y-m-d' => $this->getDate('Y-m-d'),
                        'm/d/Y' => $this->getDate('m/d/Y'),
                        'd/m/Y' => $this->getDate('d/m/Y')
                    ]
                ),
                $this->blueprint->settings(false, null, false, null, true, null, 'Your date format is currently set to ' . $this->settings->get('date_format') . ' which results in this <span class="uk-text-primary uk-text-bolder">' . $this->getDate($this->settings->get('date_format') . '</span>'))
            )
            ->add(
                $this->blueprint->select(
                    'time_format',
                    ['uk-select', 'uk-form-large', 'uk-width-1-2', 'uk-form-blank', 'uk-border-bottom'],
                    'time_format'
                ),
                $this->blueprint->choices(
                    [
                        'g:i a' => $this->getDate('g:i a'),
                        'g:i A' => $this->getDate('g:i A'),
                        'H:i' => $this->getDate('H:i'),
                    ]
                ),
                $this->blueprint->settings(false, null, false, null, true, null, 'Your time format is currently set to ' . $this->settings->get('time_format') . ' which results in this <span class="uk-text-primary uk-text-bolder">' . $this->getDate($this->settings->get('date_time') . '</span>'))
            )
            ->add(
                $this->blueprint->submit(
                    'general-settings',
                    ['uk-button', 'uk-button-primary'],
                    'Save'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}
