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
use App\DataColumns\UserColumn;

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
        return '
        <section>
            <nav aria-label="Pagination" uk-navbar>
                <div class="uk-navbar-left">
                <ul class="uk-iconnav">
                <li><button uk-tooltip="Select All" type="button" class="uk-button uk-button-small uk-button-default">
                <input type="checkbox" class="uk-checkbox" name="selectAllDomainList" id="selectAllDomainList" />
                <span></span>
                </button></li>
                <li><a href="/admin/' . $controller->thisRouteController() . '/new" uk-tooltip="Add New ' . $name . '"><span class="ion-28"><ion-icon name="add-outline"></ion-icon></span></a></li>
                <li class="uk-disabled"><button type="submit" class="uk-button uk-button-small uk-button-text" name="bulk-trash" id="bulk_trash" uk-tooltip="Bulk Delete"><span class="ion-28"><ion-icon name="trash-outline"></ion-icon></span></button></li>
                <li class="uk-disabled"><button type="submit" class="uk-button uk-button-small uk-button-text" name="bulk-copy" id="bulk_copy" uk-tooltip="Bulk Copy"><span class="ion-28"><ion-icon name="copy-outline"></ion-icon></span></button></li>
                <li><button type="submit" class="uk-button uk-button-small uk-button-text" name="notification" id="notification" uk-tooltip="Notification"><span class="ion-28"><ion-icon name="notifications-outline"></ion-icon></span></button></li>

                <li><a href="#"><span uk-icon="icon: bag"></span> (' . (isset($controller->repository) ? $controller->repository->getRepo()->count() : 0) . ')</a></li>
                 <li>
                     <div class="uk-search">
                     <a href="" class="uk-search-icon-flip" uk-search-icon></a>
                     <input type="search" class="uk-search-input uk-search-large uk-form-blank uk-border-bottom" onkeyup="tableFilter()" id="table_filter" placeholder="Filter ' . $name . '..." />
                     </div>
                 </li>
                 <li>
                    <a href="#" uk-tooltip="Filter ' . $name . '"><span class="ion-28"><ion-icon name="filter-outline"></ion-icon></span></a>
                    ' . $this->getSearchableColumns($controller) . '
                 </li>
                 <li>
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

    private function getSearchableColumns(object $controller)
    {
        $searchables = $controller->getSearchableColumns(UserColumn::class);
        if (is_array($searchables) && count($searchables) > 0) {
            $html = '<div uk-dropdown="mode: click">';
                $html .= '<ul class="uk-nav uk-nav-dropdown">';
                foreach ($searchables as $searchable) {
                    $html .= '<li>';
                    $html .= '<label for="filter-' . $searchable . '">';
                    $html .= '<input type="radio" name="filter" id="filter-' . $searchable . '" value="' . $searchable . '" class="uk-radio" />';
                    $html .= ' ' . ucwords($searchable);
                    $html .= '</label>';
                    $html .= '</li>';
                }
                $html .= '</ul>';
            $html .= '</div>';
        }
        return $html;
    }
}
