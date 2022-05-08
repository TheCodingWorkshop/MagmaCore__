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

namespace MagmaCore\Ash\Components\Uikit;

use MagmaCore\IconLibrary;
use MagmaCore\Utility\Stringify;
use MagmaCore\Ash\Traits\TemplateTraits;
use MagmaCore\Base\Traits\ControllerSessionTrait;

class UikitSimplePaginationExtension
{

    use ControllerSessionTrait,
        ControllerSessionTrait,
        TemplateTraits;

    /** @var string */
    public const NAME = 'uikit_simple_pagination';

    /**
     * Register the UIkit default pagination html wrapper
     *
     * @param object|null $controller
     * @return string
     */
    public function register(object $controller = null): string
    {
        $_name = $controller->thisRouteController();
        $name = Stringify::pluralize($_name);
        $name = Stringify::capitalize($_name);
        $filter = $this->getSessionData($_name . '_settings', $controller);

        $html = '<section class="' . $this->disabledClass($controller) . '">';
            $html .= '<nav aria-label="Pagination" uk-navbar>';
                $html .= '<div class="uk-navbar-left">';
                $html .= $this->navContentLeft($controller, $name);
                $html .= '</div>';
                $html .= '<div class="uk-navbar-center">';
                $html .= $this->navContentCentre($controller, $name);
                $html .= '</div>';
                $html .= '<div class="uk-navbar-right">';
                $html .= $this->navContentRight($controller);
                $html .= '</div>';
            $html .= '</nav>';
            $html .= '        <div class="uk-hidden uk-card uk-card-body uk-margin-bottom uk-padding-small" id="toggle-usage">';
            $html .= '
            <div class="uk-form-search">
            <span uk-search-icon></span>
            <input class="uk-search-input" name="' . $filter['filter_alias'] . '" type="search" placeholder="Filter ' . $name . '...">
            <code>You can search by [' . implode(' ', $filter['filter_by']) . '], this can be change from the settings page. <a href="/admin/' . $_name . '/settings">click here</a> filter_by option</code>
            </div>
        
        ';
            $html .= '</div>
            ';
        $html .= '</section>';

        return $html;

    }

    private function navContentLeft($controller, $name)
    {
        return '
        <ul class="uk-iconnav">
        <li><button uk-tooltip="Select All" type="button" class="uk-button uk-button-small uk-button-default">
        <input type="checkbox" class="uk-checkbox" name="selectAllDomainList" id="selectAllDomainList" />
        </button></li>
        <li><a data-turbo="true" href="/admin/' . $controller->thisRouteController() . '/new" uk-tooltip="Add New ' . $name . '">' . IconLibrary::getIcon('plus') . '</a></li>
        
        <li><button type="submit" class="uk-button uk-button-small uk-button-text" name="bulkTrash-' . $controller->thisRouteController() . '" id="bulk_trash" uk-tooltip="Bulk Trash">' . IconLibrary::getIcon('trash') . '</button></li>

        <li><button type="submit" class="uk-button uk-button-small uk-button-text" name="bulkClone-' . $controller->thisRouteController() . '" id="bulk_clone" uk-tooltip="Bulk Copy">' . IconLibrary::getIcon('copy') . '</button>
        </li>

        <li>
        <a uk-tooltip="Refresh" href="/admin/' . $controller->thisRouteController() . '/index">' . IconLibrary::getIcon('refresh') .  '</a>
        </li>

        <li>
        <a uk-toggle="cls: uk-hidden; target: #toggle-usage;" uk-tooltip="Click me" href="#">' . IconLibrary::getIcon('download') .  '</a>
        </li>

        <li><a uk-tooltip="Total ' . $name . '" class="uk-link-reset uk-text-meta" href="#"> (' . $controller->tableGrid->getTotalRecords() . ')</a></li>
        </ul>
       
        ';
    }

    private function navContentRight(object $controller): string
    {
        $html = '';
        if ($this->hasYamlSupport($controller, 'paging_top') !==false) {
            $html .= '
            <small>' . $this->infoPaging($controller) . '</small>
            <ul class="uk-pagination">
            ' . $controller->tableGrid->previousPaging($this->status($controller), $this->statusQueried($controller)) . $controller->tableGrid->nextPaging($this->status($controller), $this->statusQueried($controller)) . '
            </ul>
            ';
        } else {
            $html .= '<div class="uk-margin-large"></div>';
        }

        return $html;
    }

    private function navContentCentre(object $controller, string $name)
    {
        // return '
        // <div class="uk-search">
        //      <a href="" class="uk-search-icon-flip" uk-search-icon></a>
        //      <input type="search" class="uk-search-input uk-form-blank uk-border-bottom" onkeyup="tableFilter()" id="table_filter" placeholder="Filter ' . $name . '..." />
        //  </div>
        // ';
    }

    /**
     * Get the status from the current queried if any
     *
     * @param object $controller
     * @return string
     */
    private function status(object $controller): string
    {
        return $controller->tableGrid->getStatus();
    }

    /**
     * Return queried status value
     *
     * @param object $controller
     * @return mixed
     */
    private function statusQueried(object $controller): mixed
    {
        return $controller->tableGrid->getQueriedStatus();
    }

    /**
     * Return information regarding the pagination current count, current page
     * etc..
     *
     * @param object $controller
     * @return string
     */
    private function infoPaging(object $controller): string
    {
        return sprintf('Showing %s - %s of %s results', $controller->tableGrid->getCurrentPage(), $controller->tableGrid->getTotalPages(), $controller->tableGrid->getTotalRecords());
    }

    /**
     * Return an array of searchable column defined within the DataColumns class
     *
     * @param object $controller
     * @return void
     */
    private function getSearchableColumns(object $controller)
    {
        $searchables = $controller->getSearchableColumns($controller->column);
        if (is_array($searchables) && count($searchables) > 0) {
            $html = '<div uk-dropdown="mode: click">';
                $html .= '<ul class="uk-nav uk-nav-dropdown">';
                foreach ($searchables as $searchable) {
                    $html .= '<li>';
                    $html .= '<label for="filter-' . $searchable . '">';
                    $html .= '<input type="radio" name="filter" id="filter-' . $searchable . '" value="' . $searchable . '" class="uk-radio" />';
                    $html .= ' ' . str_replace('_', ' ', ucwords($searchable));
                    $html .= '</label>';
                    $html .= '</li>';
                }
                $html .= '</ul>';
            $html .= '</div>';
        }
        return $html;
    }
}
