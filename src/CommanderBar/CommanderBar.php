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

namespace MagmaCore\CommanderBar;

use Exception;
use MagmaCore\IconLibrary;
use MagmaCore\Utility\Stringify;
use MagmaCore\Base\BaseController;
use MagmaCore\Themes\ThemeBuilderInterface;
use MagmaCore\CommanderBar\Traits\ActionTrait;
use MagmaCore\CommanderBar\Traits\ManagerTrait;
use MagmaCore\CommanderBar\Traits\CustomizerTrait;
use MagmaCore\CommanderBar\Traits\NotifiicationTrait;

class CommanderBar implements CommanderBarInterface
{

    use CustomizerTrait;
    use ManagerTrait;
    use ActionTrait;
    use NotifiicationTrait;

    private ?ThemeBuilderInterface $themeBuilder = null;
    private BaseController $controller;

    /**
     * @throws Exception
     */
    public function __construct(BaseController $controller)
    {
        if ($controller)
            $this->controller = $controller;
        if (!$this->controller->commander instanceof ApplicationCommanderInterface) {
            throw new Exception();
        }
    }

    /**
     * Build the main commander structure and load all the necessary components
     *
     * @return string
     */
    public function build(): string
    {
        if (!in_array($this->controller->thisRouteAction(), $this->controller->commander->unsetCommander())) {
            $commander = PHP_EOL;
            $commander .= '<div style="z-index:10000;" uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; animation: uk-animation-slide-top; bottom: #transparent-sticky-navbar">';
            $commander .= '<nav class="uk-navbar" uk-navbar style="position: relative; z-index: 980; color: white!important;">';
            $commander .= PHP_EOL;
            $commander .= ' <div class="uk-navbar-left">';
            $commander .= $this->heading();
            $commander .= '<ul class="uk-navbar-nav">';
            $commander .= $this->notifications();
            $commander .= $this->manager();
            $commander .= $this->customizer();
            $commander .= '</ul>';
            $commander .= '</div>';
            $commander .= PHP_EOL;

            $commander .= '<div class="uk-navbar-center">';
            //$commander .= $this->controller->commander->getGraphs();
            $commander .= '</div>';

            $commander .= PHP_EOL;
            $commander .= '<div class="uk-navbar-right">';
            //$commander .= $this->actions();
            $commander .= '</div>';
            //commander .= $this->commanderOverlaySearch();
            $commander .= PHP_EOL;

            $commander .= '</nav>';
            $commander .= '</div>';

            return $commander;
        }

        return '';
    }


    /**
     * Undocumented function
     *
     * @param string $key
     * @return string
     */
    private function path(string $key): string
    {
        return sprintf(
            '/%s/%s/%s/%s',
            $this->controller->thisRouteNamespace(),
            $this->controller->thisRouteController(),
            $this->controller->thisRouteID(),
            $key
        );
    }



    public function heading(): string
    {
        $commanderSessionIcon = $this->controller->getSession()->get('commander_icon');
        $hasIcon = isset($commanderSessionIcon) ? $commanderSessionIcon : 'question';
        $commander = sprintf('<span class="uk-text-emphasis">%s</span>', IconLibrary::getIcon($hasIcon, 2));

        $commander .= '<a class="uk-navbar-item uk-logo uk-text-emphasis" href="">' .$this->controller->commander->getHeaderBuild($this->controller) . '</a>';
        $commander .= PHP_EOL;

        return $commander;
    }

    public function commanderFiltering()
    {
        if (isset($this->controller)) {
           if (!in_array($this->controller->thisRouteAction(), $this->controller->commander->unsetFilter())) {
                return '<a style="margin-top: -10px;" href="#" uk-tooltip="Filter Users.." class="uk-navbar-toggle uk-text-muted" uk-toggle="target: .nav-overlay; animation: uk-animation-fade">
                ' . IconLIbrary::getIcon('search') . '
                    </a>';
            }
        }

    }
    private function commanderOverlaySearch(): string
    {
        return '
        <div class="nav-overlay uk-navbar-left uk-flex-1 uk-text-small" hidden>

        <div class="uk-navbar-item uk-width-expand">
            <form class="uk-search uk-search-navbar uk-width-1-2">
                <input name="s" class="uk-search-input" type="search" placeholder="Search..." autofocus>
            </form>
        </div>

        <a class="uk-navbar-toggle uk-text-muted" uk-toggle="target: .nav-overlay; animation: uk-animation-fade" href="javascript:void()">' . IconLibrary::getIcon('close', 1.0) . '</a>

    </div>
        ';
    }


    public function __toString()
    {
        return $this->build();
    }

}
