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

class UikitPaginationExtension
{

    /** @var string */
    public const NAME = 'uikit_pagination';

    /**
     * Register the UIkit default pagination html wrapper
     *
     * @param object|null $controller
     * @return string
     */
    public function register(object $controller = null): string
    {
        return '
        <section class="">
            <nav aria-label="Pagination" uk-navbar>
                <div class="uk-navbar-left" style="margin-top: -15px;">
                </div>
                <div class="uk-navbar-right">
                ' . $this->getRowsPerPage($controller) . '
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
     * @param object $controller
     * @return string
     */
    private function getRowsPerPage(object $controller)
    {
        $tableRows = $this->getTableRows($controller);
        return '<small class="uk-margin-large-right">
        ' . sprintf('Rows per page %s', $tableRows) . '
        <div class="uk-inline">
            <span class="uk-margin-top uk-margin-left"><ion-icon name="caret-down-outline"></ion-icon></span>
            <div uk-dropdown="mode: click">
            ' . sprintf('Currently @ %s RPP.', $tableRows) . '
            <hr>
            ' . $this->dropdownForm($controller) . '
            </div>
        </div>

        </small>';
    }

    /**
     * Return the table rows per page globally or if a value is set on the controller view page. Defaults
     * to the global table row count until the controller rows per page is set.
     * @param object $controller
     * @return int
     */
    private function getTableRows(object $controller): mixed
    {
        $globalTableRows = (int)$controller->settings->get('global_table_rows_per_page');
        $controllerTableRows = $controller->controllerSettings->getRepo()
                ->findObjectBy(['controller_name' => $controller->thisRouteController()], ['records_per_page'])->records_per_page ?? 5;
        /* We want to return the greater value */
        if (!empty($globalTableRows) && !empty($controllerTableRows))
            return ($controllerTableRows >= $globalTableRows) ? $controllerTableRows : $globalTableRows;
    }

    /**
     * @param object $controller
     * @return string
     */
    private function dropdownForm(object $controller): string
    {
        $tableRow = $this->getTableRows($controller);
        $html = '<form method="post" action="/admin/' . $controller->thisRouteController() . '/change-rows" class="uk-form-horizontal">';
        $html .= '<input name="records_per_page" id="records_per_page" type="number" class="uk-input uk-form-width-small uk-form-blank uk-border-bottom" value="' . $tableRow . '" />';
        $html .= '<input type="hidden" name="controller_name" id="controller_name" value="' . $controller->thisRouteController() . '" />';
        $html .= '<button type="submit" name="rows_per_page" class="uk-button uk-button-small uk-button-primary">Go</button>';
        $html .= '</form>' . PHP_EOL;
        return $html;
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
}
