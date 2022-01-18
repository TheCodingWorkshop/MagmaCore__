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

use MagmaCore\Utility\Stringify;
use MagmaCore\UserManager\UserColumn;

class UikitSimplePaginationExtension
{

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
        $name = $controller->thisRouteController();
        $name = Stringify::pluralize($name);
        $name = Stringify::capitalize($name);
        // style="z-index: 980;" uk-sticky="offset: 80; bottom: #top; cls-active: uk-card uk-card-body uk-card-default; animation: uk-animation-slide-top"
        return '
        <section>
            <nav aria-label="Pagination" uk-navbar>
                <div class="uk-navbar-left">
                <ul class="uk-iconnav">
                <li><button uk-tooltip="Select All" type="button" class="uk-button uk-button-small uk-button-default">
                <input type="checkbox" class="uk-checkbox" name="selectAllDomainList" id="selectAllDomainList" />
                <span></span>
                </button></li>
                <li><a data-turbo="true" href="/admin/' . $controller->thisRouteController() . '/new" uk-tooltip="Add New ' . $name . '"><span class="ion-28"><ion-icon name="add-outline"></ion-icon></span></a></li>
                
                <li class=""><button type="submit" class="uk-button uk-button-small uk-button-text" name="bulk-delete" id="bulk_delete" uk-tooltip="Bulk Delete"><span class="ion-28"><ion-icon name="trash-outline"></ion-icon></span></button></li>

                <li class=""><button type="submit" class="uk-button uk-button-small uk-button-text" name="bulk-clone" id="bulk_clone" uk-tooltip="Bulk Copy"><span class="ion-28"><ion-icon name="copy-outline"></ion-icon></span></button>
                </li>

                <li><a class="uk-link-reset" href="#"><span uk-icon="icon: bag"></span> (' . (isset($controller->repository) ? $controller->repository->getRepo()->count() : 0) . ')</a></li>
                 <li>
                     <div class="uk-search">
                     <a href="" class="uk-search-icon-flip" uk-search-icon></a>
                     <input type="search" class="uk-search-input uk-search-large uk-form-blank uk-border-bottom" onkeyup="tableFilter()" id="table_filter" placeholder="Filter ' . $name . '..." />
                     </div>
                 </li>
                </ul>
                </div>
                <div class="uk-navbar-right">
                    <small>' . $this->infoPaging($controller) . '</small>
                    <ul class="uk-pagination">
                    ' . $controller->tableGrid->previousPaging($this->status($controller), $this->statusQueried($controller)) . $controller->tableGrid->nextPaging($this->status($controller), $this->statusQueried($controller)) . '
                    </ul>
                </div>
            </nav>
        </section>
        ' . PHP_EOL;

    //     <li>
    //     <a href="#" uk-tooltip="Filter ' . $name . '"><span class="ion-28"><ion-icon name="filter-outline"></ion-icon></span></a>
    //     ' . $this->getSearchableColumns($controller) . '
    //  </li>

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
        return sprintf('%s - %s of %s', $controller->tableGrid->getCurrentPage(), $controller->tableGrid->getTotalPages(), $controller->tableGrid->getTotalRecords());
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
