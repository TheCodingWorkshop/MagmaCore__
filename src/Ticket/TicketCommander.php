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

use MagmaCore\CommanderBar\ApplicationCommanderInterface;
use MagmaCore\CommanderBar\ApplicationCommanderTrait;
use MagmaCore\CommanderBar\CommanderUnsetterTrait;
use MagmaCore\Utility\Stringify;
use Exception;

class TicketCommander extends TicketModel implements ApplicationCommanderInterface
{

    use ApplicationCommanderTrait;
    use CommanderUnsetterTrait;

    /**
     * Return an array of all the inner routes within the user model
     * @var array
     */
    protected const INNER_ROUTES = [
        'index',
        'new',
        'edit',
        'show',
        'log',
        'bulk',
        'trash',
        'hard-delete',
    ];

    private array $noCommander = [];
    private array $noNotification = self::INNER_ROUTES;
    private array $noCustomizer = ['edit', 'show', 'new', 'trash', 'bulk'];
    private array $noManager = ['trash', 'new', 'bulk'];
    private array $noAction = ['trash'];
    private array $noFilter = ['edit', 'show', 'new', 'trash'];

    private object $controller;

    /**
     * Get the specific yaml file which helps to render some text within the specified
     * html template.
     *
     * @return array
     * @throws Exception
     */
    public function getYml(): array
    {
        return $this->findYml('ticket');
    }

    /**
     * Display a sparkline graph for this controller index route
     *
     * @return string
     */
    public function getGraphs(): string
    {
        return '<div id="sparkline"></div>';
    }

    /**
     * Dynamically change commander bar header based on the current route
     *
     * @param object $controller
     * @return string
     * @throws Exception
     */
    public function getHeaderBuild(object $controller): string
    {
        $this->getHeaderBuildException($controller, self::INNER_ROUTES);
        $this->controller = $controller;
        $suffix = $this->getHeaderBuildEdit($controller, 'ticket_name');

        return match ($controller->thisRouteAction()) {
            'index' => $this->getStatusColumnFromQueryParams($controller),
            'new' => 'Create New',
            'edit' => "Edit " . $this->getHeaderBuildEdit($controller, 'ticket_name'),
            'show' => "Viewing " . $suffix,
            'bulk' => (isset($_POST['bulk-delete']) ? 'Bulk Delete' : 'Bulk Cloning'),
            'trash' => 'Trash Listings',
            'log' => Stringify::capitalize($controller->thisRouteController()) . ' Log',
            'hard-delete' => "Deleting " . $suffix,
            default => "Unknown"
        };
    }

}

