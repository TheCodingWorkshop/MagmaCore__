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

namespace MagmaCore\System\App\Commander;

use MagmaCore\System\App\Model\EventModel;
use MagmaCore\CommanderBar\ApplicationCommanderInterface;
use MagmaCore\CommanderBar\ApplicationCommanderTrait;
use MagmaCore\CommanderBar\CommanderUnsetterTrait;
use Exception;

class SystemCommander extends EventModel implements ApplicationCommanderInterface
{

    use ApplicationCommanderTrait;
    use CommanderUnsetterTrait;

    /**
     * Return an array of all the inner routes within the user model
     * @var array
     */
    protected const INNER_ROUTES = [
        'index',
        'show',
        'trash',
    ];

    private array $noCommander = ['index'];
    private array $noNotification = self::INNER_ROUTES;
    private array $noCustomizer = ['trash'];
    private array $noManager = ['trash'];
    private array $noAction = [];
    private array $noFilter = ['trash'];

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
        return $this->findYml('system');
    }

    /**
     * Display a sparkline graph for this controller index route
     *
     * @return string
     */
    public function getGraphs(): string
    {
        return '';
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
        $suffix = $this->getHeaderBuildEdit($controller, 'menu_name');

        return match ($controller->thisRouteAction()) {
            'index' => $this->getStatusColumnFromQueryParams($controller),
            'show' => 'View Log Entry #' . $controller->thisRouteID(),
            'trash' => 'All Trash',
            default => "Unknown"
        };
    }

}


