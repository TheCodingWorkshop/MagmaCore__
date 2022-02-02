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

namespace MagmaCore\Ticket;

use Exception;
use MagmaCore\Base\Access;
use MagmaCore\Utility\Yaml;

class TicketController extends \MagmaCore\Administrator\Controller\AdminController
{

    /**
     * Extends the base constructor method. Which gives us access to all the base
     * methods implemented within the base controller class.
     * Class dependency can be loaded within the constructor by calling the
     * container method and passing in an associative array of dependency to use within
     * the class
     *
     * @param array $routeParams
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        /**
         * Dependencies are defined within a associative array like example below
         * [ userModel => \App\Model\UserModel::class ]. Where the key becomes the
         * property for the userModel object like so $this->userModel->getRepo();
         */
        $this->addDefinitions(
            [
                'repository' => TicketModel::class,
                'column' => TicketColumn::class,
                'commander' => TicketCommander::class,
                'ticketForm' => TicketForm::class,
                'ticketEntity' => TicketEntity::class
            ]
        );

        /** Initialize database with table settings */
    }

    /**
     * Return an array of template context which is accessible from any route within this controller
     * @return array
     */
    protected function controllerViewGlobals(): array
    {
        return [
            'ticket_message_sidebar' => $this->repository->ticketStatusMenu()
        ];
    }

    /**
     * Returns a 404 error page if the data is not present within the database
     * else return the requested object
     *
     * @return mixed
     */
    public function findOr404(): mixed
    {
        if (isset($this)) {
            return $this->repository->getRepo()
                ->findAndReturn($this->thisRouteID())
                ->or404();
        }
    }

    /**
     * Display all tickets within a datatable route
     *
     * @return void
     */
    protected function indexAction()
    {
        $this->indexAction
            ?->setAccess($this, Access::CAN_VIEW)
            ?->execute($this, NULL, NULL, TicketSchema::class, __METHOD__)
            ?->render()
            ?->with(
                [
                    'queried_status' => $this->request->handler()->query->getAlnum('status') ?: '',
                    'table_schema' => (string)$this->repository->getSchema()
                ]
            )
            ?->table()
            ?->end();
    }

    /**
     * New action route for addiing a new ticket
     *
     * @return void
     */
    protected function newAction()
    {
        $this->newAction
            ->execute($this, TicketEntity::class, TicketActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with()
            ->form($this->ticketForm)
            ->end();
    }

    /**
     * The edit action request. is responsible for updating a ticket record within
     * the database. ticket data will be sanitized and validated before upon re
     * submitting new data. An event will be dispatched on this action
     */
    protected function editAction()
    {
        $this->editAction
            ->setAccess($this, Access::CAN_EDIT)
            ->setOwnerAccess($this)
            ->execute($this, TicketEntity::class, TicketActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with(
                [
                ]
            )
            ->form($this->ticketForm)
            ->end();
    }

}

