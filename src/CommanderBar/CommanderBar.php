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
use MagmaCore\Themes\ThemeBuilder;
use MagmaCore\Themes\ThemeBuilderInterface;
use MagmaCore\CommanderBar\CommanderBarInterface;

class CommanderBar implements CommanderBarInterface
{

    private ?ThemeBuilderInterface $themeBuilder = null;
    private array $managerItems = [];
    private array $visibleColumns = [];
    private object $controller;
    private object|null $formSettings = null;
    private string|null $heading = null;
    private string|null $icon = null;
    private string|null $tableForm = null;

    public function __construct(?ThemeBuilder $themeBuilder = null)
    {
        $this->themeBuilder = $themeBuilder;
    }

    /**
     * Undocumented function
     *
     * @param object $controller
     * @param array $managerItems
     * @param array $visibleColumns
     * @param object|null $formSettings
     * @param string|null $icon
     * @param string|null $heading
     * @return void
     */
    public function create(
        object $controller,
        array $managerItems = [],
        array $visibleColumns = [],
        ?object $formSettings = null,
        ?string $icon = null,
        ?string $heading,
        ?string $tableForm = null
    ): void {
        if ($managerItems)
            $this->managerItems = $managerItems;
        if ($controller)
            $this->controller = $controller;
        if ($formSettings)
            $this->formSettings = $formSettings;
        if ($visibleColumns)
            $this->visibleColumns = $visibleColumns;
        if ($icon !== null)
            $this->icon = $icon;
        if ($heading !== null)
            $this->heading = $heading;
        if ($tableForm !==null)
            $this->tableForm = $tableForm;
    }

    public function commanderWrapper()
    {
        $commander = '';
        $commander .= PHP_EOL;
        $commander .= '<div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; bottom: #transparent-sticky-navbar">';
        $commander .= '<nav class="uk-navbar" uk-navbar style="position: relative; z-index: 980; color: white!important;">';

        $commander .= PHP_EOL;
        $commander .= ' <div class="uk-navbar-left">';
        $commander .= $this->commanderHeading();
        $commander .= '<ul class="uk-navbar-nav">';
        $commander .= $this->commanderNotifications();
        $commander .= $this->commanderManager();
        $commander .= $this->commanderCustomizer();
        $commander .= '</ul>';
        $commander .= '</div>';
        $commander .= PHP_EOL;

        $commander .= PHP_EOL;
        $commander .= '<div class="uk-navbar-right">';
        $commander .= $this->commanderAction();
        $commander .= '</div>';
        $commander .= PHP_EOL;

        $commander .= '</nav>';
        $commander .= '</div>';

        return $commander;
    }

    public function commanderManager()
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
        
        if (is_array($this->managerItems) && count($this->managerItems) > 0) {
            $commander .= '<ul class="uk-nav uk-navbar-dropdown-nav">';
            foreach ($this->managerItems as $key => $value) {
                if (isset($value['nav_header']) && $value['nav_header'] !=='') {
                    $commander .= '<li class="uk-nav-header">' . $value['nav_header'] . '</li>';
                }
                if ($this->controller->thisRouteAction() === $key) {
                    unset($value);
                }
                $commander .= PHP_EOL;
                $commander .= '<li>';
                $commander .= '<a href="' . (isset($value['path']) ? $value['path'] : $this->path($key)) . '">';
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
        $commander .= '<h3 class="uk-card-title">Statistics</h3>';
        $commander .= '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>';
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

    private function path($key)
    {
        return sprintf(
            '/%s/%s/%s/%s',
            $this->controller->thisRouteNamespace(),
            $this->controller->thisRouteController(),
            $this->controller->thisRouteID(),
            $key
        );
    }

    public function commanderCustomizer()
    {
        if (isset($this->controller)) {
            if (in_array($this->controller->thisRouteAction(), ['edit', 'show', 'new'])) {
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
        if (is_array($this->visibleColumns)) {
            foreach ($this->visibleColumns as $column) {
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
        $commander .= $this->tableForm ? $this->tableForm : '<span class="ion-64 uk-float-left"><ion-icon name="alert-circle-outline"></ion-icon></span><small class="uk-float-left uk-margin-medium-top">Settings Unavailable.</small>';
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

    public function commanderNotifications()
    {
        if (isset($this->controller)) {
            if (in_array($this->controller->thisRouteAction(), ['edit', 'show', 'new'])) {
                return '';
            }
        }
        //$off = '<ion-icon name="notifications-off-outline"></ion-icon>';
        $commander = '';
        $commander .= '<li class="uk-active">';
        $commander .= '<a href="#" class="uk-text-muted">';
        $commander .= '<ion-icon size="large" name="notifications-outline"></ion-icon>';
        $commander .= '<span><sup class="uk-badge">3</sup></span>';
        $commander .= '</a>';
        $commander .= '</li>';

        return $commander;
    }

    public function commanderHeading()
    {
        $commander = '';
        $commander .= '<span class="ion-32 uk-text-emphasis"><ion-icon name="' . (isset($this->icon) ? $this->icon : 'help-outline') . '"></ion-icon></span>';
        $commander .= '<a class="uk-navbar-item uk-logo uk-text-emphasis" href="#">' . (isset($this->heading) ? $this->heading : 'Unknow Heading') . '</a>';
        $commander .= PHP_EOL;

        return $commander;
    }

    public function commanderAction()
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
            if (in_array($this->controller->thisRouteAction(), ['new', 'edit', 'show'])) {
                // return '<a href="/admin/user/new" uk-tooltip="Add New" class="ion-28 uk-margin-small-right uk-text-muted">
                // <ion-icon name="add-circle-outline"></ion-icon>
                // </a>';
                return '';
            }
        }

        return '
       <a href="#" uk-tooltip="Filter Users.." class="ion-28 uk-margin-small-right uk-text-muted">
        <ion-icon name="funnel-outline"></ion-icon>
        </a>

        <div class="uk-navbar-dropdown" uk-drop="mode: click; cls-drop: uk-navbar-dropdown; boundary: !nav">

        <div class="uk-grid-small uk-flex-middle" uk-grid>
            <div class="uk-width-expand">
                <form class="uk-search uk-search-navbar uk-width-1-1">
                    <input class="uk-search-input" name="s" type="search" placeholder="Filter..." autofocus>
                    <hr/>
                    <div class="uk-margin">
                        <div class="uk-form-label"></div>
                        <div class="uk-form-controls">
                            <label><input class="uk-radio" type="radio" name="radio1"> Firstnmae</label><br>
                            <label><input class="uk-radio" type="radio" name="radio1"> Lastname</label><br>
                            <label><input class="uk-radio" type="radio" name="radio1"> Email</label><br>
                            <label><input class="uk-radio" type="radio" name="radio1"> ID</label>
    
                        </div>
                    </div>
                </form>
            </div>
            <div class="uk-width-auto">
                <a class="uk-navbar-dropdown-close" href="#" uk-close></a>
            </div>
        </div>
    
    </div>
        ';
    }

    private function actionButton()
    {
        if (isset($this->controller)) {
            return match ($this->controller->thisRouteAction()) {
                'new', 'edit', 'show', 'hard-delete' => 'Listings',
                default => 'Add new'
            };
        }
    }

    private function actionPath()
    {
        if (isset($this->controller)) {
            return match ($this->controller->thisRouteAction()) {
                'new', 'edit', 'show', 'hard-delete' => '/' . $this->controller->thisRouteNamespace() . '/' . $this->controller->thisRouteController() . '/' . 'index',
                'index' => '/admin/user/new',
                default => 'javascript:history.back()'
            };
        }
    }
}
