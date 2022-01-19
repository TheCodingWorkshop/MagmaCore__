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

class UserNotesForm extends ClientFormBuilder implements ClientFormBuilderInterface
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
                $this->blueprint->textarea(
                    'notes',
                    ['uk-textarea', 'uk-width-1-1', 'uk-height-large'],
                    'notes',
                    'Add Notes'
                ),
                $this->hasValue('notes'),
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
                    $this->hasValue('id') ? 'notes-user' : 'notes-user',
                    ['uk-button', 'uk-button-primary', 'uk-form-width-medium'],
                    'Add Note'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}
