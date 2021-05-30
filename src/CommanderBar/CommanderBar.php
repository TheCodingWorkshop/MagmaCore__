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

use MagmaCore\Utility\Stringify;
use MagmaCore\Base\BaseController;
use MagmaCore\Themes\ThemeBuilderInterface;
use MagmaCore\CommanderBar\CommanderBarInterface;
use MagmaCore\CommanderBar\ApplicationCommanderInterface;

class CommanderBar implements CommanderBarInterface
{

    private ?ThemeBuilderInterface $themeBuilder = null;
    private $controller;

    public function __construct(BaseController $controller)
    {
        if ($controller)
            $this->controller = $controller;
        if (!$this->controller->commander instanceof ApplicationCommanderInterface) {
            throw new \Exception();
        }
    }

    /**
     * Build the main commander structure and load all the necessary components
     *
     * @return string
     */
    public function build(): string
    {
        $commander = '';
        $commander .= PHP_EOL;
        $commander .= '<div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; animation: uk-animation-slide-top; bottom: #transparent-sticky-navbar">';
        $commander .= '<nav class="uk-navbar" uk-navbar style="position: relative; z-index: 980; color: white!important;">';
        $commander .= PHP_EOL;
        $commander .= ' <div class="nav-overlay uk-navbar-left">';
        $commander .= $this->heading();
        $commander .= '<ul class="uk-navbar-nav">';
        $commander .= $this->notifications();
        $commander .= $this->manager();
        $commander .= $this->customizer();
        $commander .= '</ul>';
        $commander .= '</div>';
        $commander .= PHP_EOL;

        $commander .= '<div class="nav-overlay uk-navbar-center">';
        $commander .= $this->controller->commander->getGraphs();
        $commander .= '</div>';

        $commander .= PHP_EOL;
        $commander .= '<div class="nav-overlay uk-navbar-right">';
        $commander .= $this->actions();
        $commander .= '</div>';
        $commander .= $this->commanderOverlaySearch();
        $commander .= PHP_EOL;

        $commander .= '</nav>';
        $commander .= '</div>';

        return $commander;
    }

    private function manager()
    {
        if (isset($this->controller)) {
            if (in_array($this->controller->thisRouteAction(), ['new'])) {
                return '';
            }
        }

        $commander = '';
        $commander .= PHP_EOL;
        $commander .= '<li>';
        $commander .= '<a href="#"><ion-icon size="large" name="home-outline"></ion-icon></a>';
        $commander .= '<div uk-dropdown="mode: click" class="uk-navbar-dropdown uk-navbar-dropdown-width-3">';
        $commander .= '<div class="uk-navbar-dropdown-grid uk-child-width-1-3" uk-grid>';
        $commander .= '<div class="uk-width-1-3">';

        if (is_array($lists = $this->controller->commander->getYml()) && count($lists) > 0) {
            $commander .= '<ul class="uk-nav uk-navbar-dropdown-nav">';
            foreach ($lists as $key => $value) {
                if (isset($value['nav_header']) && $value['nav_header'] !== '') {
                    $commander .= '<li class="uk-nav-header">' . $value['nav_header'] . '</li>';
                }
                if ($this->controller->thisRouteAction() === $key) {
                    unset($value);
                }
                $commander .= PHP_EOL;
                $commander .= '<li>';
                $commander .= '<a uk-toggle="target: #toggle-custom; cls: highlight" href="' . (isset($value['path']) ? $value['path'] : $this->path($key)) . '">';
                $commander .= (isset($value['name']) ? Stringify::capitalize($value['name']) : '');
                $commander .= '</a>';
                $commander .= '</li>';
                $commander .= PHP_EOL;
            }
            $commander .= '<li class="uk-nav-divider"></li>';
            $commander .= '<li>';
            $commander .= '<a href="" uk-tooltip="View Trash" class="ion-28">';
            $commander .= '<ion-icon name="trash"></ion-icon>';
            $commander .= '</a>';
            $commander .= '</li>';
            $commander .= '</ul>';
        }

        $commander .= '</div>';
        $commander .= PHP_EOL;
        $commander .= '<div class="uk-width-expand">';
        $commander .= '<div>';
        $commander .= '<div class="uk-card">';
        if (isset($this->controller)) {
            if (in_array($this->controller->thisRouteAction(), ['new'])) {
                $commander .= '<h3 class="uk-card-title">Change Status</h3>';
                if (is_array($statusColumns = $this->controller->repository->getColumnStatus()) && count($statusColumns) > 0) {
                    $commander .= '<ul class="uk-nav uk-dropdown-nav">';
                    foreach ($statusColumns as $key => $value) {
                        foreach ($value as $val) {
                            $commander .= '<li>';
                            $commander .= '<a class="uk-text-success uk-link-reset uk-text-capitalize" href="?' . $key . '=' . $val . '">';
                            $badgeColor = ['pending' => 'warning', 'active' => 'success', 'trash' => 'danger', 'lock' => 'secondary'];
                            $commander .= '<span class="uk-badge uk-badge-' . $badgeColor[$val] . ' uk-margin-small-right">';
                            $commander .= $this->controller->repository->getRepo()->count([$key => $val]);
                            $commander .= '</span>' . Stringify::capitalize($val);
                            $commander .= '</a>';
                            $commander .= '';
                            $commander .= '</li>';
                        }
                    }
                    $commander .= '<li class="uk-nav-divider"></li>';
                    $commander .= '<li><a class="ion-24" href="/admin/user/index">';
                    $commander .= '<ion-icon size="large" name="home-outline"></ion-icon> <span class="uk-text-meta"><span class="uk-badge uk-text-bolder">' . $this->controller->repository->getRepo()->count() . ' </span> total records within this model</span></a>';
                    $commander .= '</li>';
                    $commander .= '</ul>' . PHP_EOL;
                }
            } else {
                    $commander .= '<p>Not enough data to generate a graph</p>';
                /* display individual user graph */
            }
        }

        $commander .= '</div>';
        $commander .= '</div>';
        $commander .= '</div>';
        $commander .= PHP_EOL;
        $commander .= '</div>';
        $commander .= '</div>';
        $commander .= '</li>';
        $commander .= PHP_EOL;

        return $commander;
    }

    /**
     * Undocumented function
     *
     * @param string $key
     * @return string
     */
    private function path($key): string
    {
        return sprintf(
            '/%s/%s/%s/%s',
            $this->controller->thisRouteNamespace(),
            $this->controller->thisRouteController(),
            $this->controller->thisRouteID(),
            $key
        );
    }

    private function customizer()
    {
        if (isset($this->controller)) {
            if (in_array($this->controller->thisRouteAction(), ['edit', 'show', 'new', 'perferences', 'privileges'])) {
                return '';
            }
        }
        $commander = '';
        $commander .= '<li>';
        $commander .= '<a href="#"><ion-icon size="large" name="settings-outline"></ion-icon></a>';
        $commander .= '<div uk-dropdown="mode: click" class="uk-navbar-dropdown uk-navbar-dropdown-width-3">';

        $commander .= '<div class="uk-navbar-dropdown-grid uk-child-width-1-3" uk-grid>';

        $commander .= '<div class="uk-width-1-3">';
        $commander .= '<div class="uk-margin">';
        $commander .= '<div class="uk-form-label">Toggle Columns</div>';
        $commander .= '<hr>';
        $commander .= PHP_EOL;
        $commander .= '<div class="uk-form-controls">';
        if (is_array($columns = $this->controller->tableGrid->getDataColumns())) {
            foreach ($columns as $column) {
                if (isset($column['show_column']) && $column['show_column'] != false) {
                    if ($column['db_row'] === 'id') {
                        $column['show_column'] = true;
                    }
                    $commander .= '<label uk-toggle="target: #toggle-' . $column['db_row'] . '">';
                    $commander .= '<input class="uk-checkbox" type="checkbox" name="' . $column['db_row'] . '">';
                    $commander .= ' ' . Stringify::capitalize($column['dt_row']);
                    $commander .= '</label>';
                    $commander .= '<br>';
                    $commander .= PHP_EOL;
                }
            }
        }
        $commander .= '</div>';
        $commander .= PHP_EOL;
        $commander .= '</div>';
        $commander .= '</div>';
        $commander .= PHP_EOL;

        $commander .= '<div class="uk-width-expand">';
        $commander .= '<div>';
        $commander .= '<div class="uk-card">';
        $commander .= '<h3 class="uk-card-title">Settings</h3>';
        $commander .= $this->controller
            ->controllerSettings
            ->createForm(
                "/admin/{$this->controller->thisRouteController()}/settings",
                $this->controller
                    ->controllerRepository
                    ->getRepo()
                    ->findObjectBy(['controller_name' => $this->controller->thisRouteController()]) ?? '<span class="ion-64 uk-float-left"><ion-icon name="alert-circle-outline"></ion-icon></span><small class="uk-float-left uk-margin-medium-top">Settings Unavailable.</small>',
                $this->controller
            );

        $commander .= '</div>';
        $commander .= '</div>';
        $commander .= '</div>';
        $commander .= PHP_EOL;

        $commander .= '</div>';
        $commander .= '</div>';
        $commander .= '</li>';
        $commander .= PHP_EOL;

        return $commander;
    }

    public function notifications()
    {
        if (isset($this->controller)) {
            if (in_array($this->controller->thisRouteAction(), ['edit', 'show', 'new', 'perferences', 'privileges'])) {
                return '';
            }
        }
        //$off = '<ion-icon name="notifications-off-outline"></ion-icon>';
        $commander = '';
        $commander .= '<li class="uk-active">';
        $commander .= '<a href="javascript:void()" class="uk-text-muted">';
        $commander .= '<ion-icon size="large" name="notifications-outline"></ion-icon>';
        $commander .= '<span><sup class="uk-badge">3</sup></span>';
        $commander .= '</a>';
        $commander .= '</li>';

        return $commander;
    }

    public function heading()
    {
        $commander = '';
        $commander .= '<span class="ion-32 uk-text-emphasis"><ion-icon name="help-outline"></ion-icon></span>';
        $commander .= '<a class="uk-navbar-item uk-logo uk-text-emphasis" href="#">' .$this->controller->commander->getHeaderBuild($this->controller) . '</a>';
        $commander .= PHP_EOL;

        return $commander;
    }

    public function actions()
    {
        $commander = '';
        $commander .= PHP_EOL;
        $commander .= $this->commanderFiltering(); // filtering
        $commander .= '<ul class="uk-iconnav">';
        $commander .= '<li>';
        $commander .= '<a href="#" uk-tooltip="View Log" class="ion-28">';
        $commander .= '<ion-icon name="reader-outline"></ion-icon>';
        $commander .= '</a>';
        $commander .= '</li>';
        $commander .= PHP_EOL;
        $commander .= '<li>';
        $commander .= '<a href="' . $this->actionPath() . '" uk-tooltip="Go Back" class="uk-button uk-button-primary uk-button-small uk-link-reset uk-link-toggle">';
        $commander .= $this->actionButton();
        $commander .= '</a>';
        $commander .= '</li>';
        $commander .= PHP_EOL;
        $commander .= '</ul>';

        return $commander;
    }

    public function commanderFiltering()
    {
        if (isset($this->controller)) {
            if (in_array($this->controller->thisRouteAction(), ['new', 'edit', 'show', 'perferences', 'privileges'])) {
                // return '<a href="/admin/user/new" uk-tooltip="Add New" class="ion-28 uk-margin-small-right uk-text-muted">
                // <ion-icon name="add-circle-outline"></ion-icon>
                // </a>';
                return '';
            }
        }

        return '
       <a style="margin-top: -10px;" href="#" uk-tooltip="Filter Users.." class="uk-navbar-toggle ion-28 uk-text-muted" uk-toggle="target: .nav-overlay; animation: uk-animation-fade">
        <ion-icon name="funnel-outline"></ion-icon>
        </a>

        ';
    }
    private function commanderOverlaySearch()
    {
        return '
        <div class="nav-overlay uk-navbar-left uk-flex-1" hidden>

        <div class="uk-navbar-item uk-width-expand">
            <form class="uk-search uk-search-navbar uk-width-1-1">
                <input name="s" class="uk-search-input" type="search" placeholder="Filtering ' . Stringify::pluralize(ucwords($this->controller->thisRouteController())) . '...." autofocus>
            </form>
        </div>

        <a class="uk-navbar-toggle uk-text-muted" uk-toggle="target: .nav-overlay; animation: uk-animation-fade" href="javascript:void()"><ion-icon size="large" name="close-outline"></ion-icon></a>

    </div>
        ';
    }

    private function actionButton()
    {
        if (isset($this->controller)) {
            return match ($this->controller->thisRouteAction()) {
                'new', 'edit', 'show', 'hard-delete', 'perferences', 'privileges' => 'Listings',
                default => 'Add new'
            };
        }
    }

    private function actionPath()
    {
        if (isset($this->controller)) {
            return match ($this->controller->thisRouteAction()) {
                'new', 'edit', 'show', 'hard-delete', 'perferences', 'privileges' => '/' . $this->controller->thisRouteNamespace() . '/' . $this->controller->thisRouteController() . '/' . 'index',
                'index' => '/admin/' . $this->controller->thisRouteController() . '/new',
                default => 'javascript:history.back()'
            };
        }
    }

    public function __toString()
    {
        return $this->build();
    }

}
