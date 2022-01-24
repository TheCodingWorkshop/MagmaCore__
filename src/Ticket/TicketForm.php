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

namespace MagmaCore\Ticket;

use Exception;
use MagmaCore\DataObjectLayer\DataLayerTrait;
use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;
use MagmaCore\Utility\Yaml;
use MagmaCore\UserManager\Rbac\Role\RoleModel;
use MagmaCore\UserManager\Model\UserRoleModel;

class TicketForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

    use DataLayerTrait;
    /** @var FormBuilderBlueprintInterface $blueprint */
    private FormBuilderBlueprintInterface $blueprint;
    private TicketModel $ticketModel;

    /**
     * Main class constructor
     *
     * @param FormBuilderBlueprint $blueprint
     * @param TicketModel $ticketModel
     */
    public function __construct(FormBuilderBlueprint $blueprint, TicketModel $ticketModel)
    {
        $this->blueprint = $blueprint;
        $this->ticketModel = $ticketModel;
        parent::__construct();
    }

    public function getModel(): TicketModel
    {
        return $this->ticketModel;
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
        return $this->form(['action' => $action, 'class' => ['uk-form-stacked'], "id" => "permissionForm"])
            ->addRepository($dataRepository)
            ->add(
                $this->blueprint->text('assigned_to', ['uk-form-blank', 'uk-border-bottom'], $this->hasValue('assigned_to'), false, 'Assigned To:'),
                NULL,
                $this->blueprint->settings(false, null, false, null, true)

            )
            ->add(
                $this->blueprint->text('subject', ['uk-form-blank', 'uk-border-bottom'], $this->hasValue('subject'), false, 'Subject'),
                NULL,
                $this->blueprint->settings(false, null, false, null, true)

            )
            ->add(
                $this->blueprint->textarea('ticket_desc', ['uk-textarea', 'uk-form-blank'], null, 'Message'),
                $this->hasValue('ticket_desc'),
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->add(
                $this->blueprint->upload('attachment', ['uk-button', 'uk-button-small', 'uk-button-default'], 'Upload', true),
                '<span class="ion-28"><ion-icon name="attach"></ion-icon></span>',
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->add(
                $this->blueprint->select(
                    'status',
                    ['uk-select', 'uk-form-width-small', 'uk-form-blank', 'uk-border-bottom'],
                    'id',
                    $this->hasValue('status')
                ),
                $this->blueprint->choices(['open', 'closed', 'resolved'], 'open'),
                $this->blueprint->settings(false, null, false, null, true, null, 'Status.')
            )
            ->add(
                $this->blueprint->select(
                    'priority',
                    ['uk-select', 'uk-form-width-small', 'uk-form-blank', 'uk-border-bottom'],
                    'id',
                    $this->hasValue('priority')
                ),
                $this->blueprint->choices(['low', 'medium', 'high', 'critical'], 'low'),
                $this->blueprint->settings(false, null, false, null, true, null, 'Priority.')
            )
            ->add(
                $this->blueprint->select(
                    'category',
                    ['uk-select', 'uk-form-width-small', 'uk-form-blank', 'uk-border-bottom'],
                    'id',
                    $this->hasValue('category')
                ),
                $this->blueprint->choices(['general', 'technical', 'other'], 'technical'),
                $this->blueprint->settings(false, null, false, null, true, null, 'Category.')
            )

            ->add(
                $this->blueprint->submit(
                    $this->hasValue('id') ? 'edit-ticket' : 'new-ticket',
                    ['uk-button', 'uk-button-primary', 'uk-form-width-medium'],
                    'Update'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}

