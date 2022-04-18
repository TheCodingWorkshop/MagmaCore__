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

namespace MagmaCore\History;

use MagmaCore\CommanderBar\ApplicationCommanderInterface;
use MagmaCore\CommanderBar\ApplicationCommanderTrait;
use MagmaCore\CommanderBar\CommanderUnsetterTrait;
use Exception;

class HistoryCommander extends HistoryModel implements ApplicationCommanderInterface
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
        'settings'
    ];

    private array $noCommander = [];
    private array $noNotification = self::INNER_ROUTES;
    private array $noCustomizer = ['edit', 'show', 'new'];
    private array $noManager = [];
    private array $noAction = [];
    private array $noFilter = ['edit', 'show', 'new'];

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
        return $this->findYml('history');
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
        $suffix = $this->getHeaderBuildEdit($controller, 'history_path');

        return match ($controller->thisRouteAction()) {
            'index' => $this->getStatusColumnFromQueryParams($controller),
            'settings' => 'Menu Settings',
            default => "Unknown"
        };
    }

}

