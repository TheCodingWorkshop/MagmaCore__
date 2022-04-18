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

namespace MagmaCore\UserManager\Forms\Admin;

use Exception;
use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;

class UserPreferencesForm extends ClientFormBuilder implements ClientFormBuilderInterface
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
     * @throws Exception
     */
    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null): string
    {
        return $this->form(['action' => $action, 'class' => ['uk-form-stacked'], "id" => "userPreferencesForm"])
            ->addRepository($dataRepository)
            ->add(
                $this->blueprint->select(
                    'language',
                    ['uk-select']
                ),
                $this->blueprint->choices(['en_GB', 'en_US', 'fr', 'es', 'de'], $this->hasValue('language'), $this),
                $this->blueprint->settings(false, null, true, 'Language', true, null, 'The language that the control panel should use.')
            )
            ->add(
                $this->blueprint->select(
                    'week_start_on',
                    ['uk-select', 'uk-form-width-small'],
                    'week_start_on',
                    $this->hasValue('week_start_on')
                ),
                $this->blueprint->choices(['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'], $this->hasValue('week_start_on')),
                $this->blueprint->settings(false, null, true, 'Week Starts On', true, null, 'Choose the what the week starts.')
            )
            ->add(
                $this->blueprint->checkbox(
                    'enable_notification',
                    ['uk-checkbox'],
                    $this->hasValue('enable_notification')
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true, 'Enable Notifications')
            )
            ->add(
                $this->blueprint->textarea(
                    'address',
                    ['uk-textarea'],
                    'address',
                    $this->getRepository()->address . ' address (optional)'
                ),
                $this->hasValue('address'),
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->add(
                $this->blueprint->hidden(
                    'user_id',
                    $dataRepository->user_id,
                    [],
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )

            ->add(
                $this->blueprint->submit(
                    $this->hasValue('id') ? 'preferences-user' : 'preferences-user',
                    ['uk-button', 'uk-button-secondary', 'uk-form-width-medium'],
                    'Update'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}
