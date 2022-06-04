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

use MagmaCore\Ash\Traits\TemplateTraits;
use MagmaCore\Base\Traits\ControllerSessionTrait;
use MagmaCore\IconLibrary;

class UikitPaginationExtension
{

    use TemplateTraits;
    use ControllerSessionTrait;

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
        $html = '';
        $html .= '
        <section class="' . $this->disabledClass($controller) . '">
            <nav aria-label="Pagination" uk-navbar>
                <div class="uk-navbar-left" style="margin-top: -15px;">';
                
                if ($this->hasYamlSupport($controller, 'trash_can_support') !==false) {
                    $html .= $this->repositoryTrash($controller);
                } else {
                    $html .= '';
                }
                $html .= '</div>';

                if ($this->hasYamlSupport($controller, 'paging_bottom') !==false) {
                $html .= '<div class="uk-navbar-right">
                ' . $this->getRowsPerPage($controller) . '
                <small>' . $this->infoPaging($controller) . '</small>
                    <ul class="uk-pagination">';

                    $html .= $controller->tableGrid->previousPaging($this->status($controller), $this->statusQueried($controller));
                    $html .= $controller->tableGrid->pagingSteps();
                    $html .= $controller->tableGrid->nextPaging($this->status($controller), $this->statusQueried($controller));
                    
                    $html .= '</ul>
                </div>';
                } else {
                    $html .= '<div class="uk-navbar-right uk-margin"></div>';
                }

            $html .= '</nav>
        </section>
        ' . PHP_EOL;

        return $html;
    }

    private function controllerName(object $controller): string
    {
        return $controller->thisRouteController();
    }

    /**
     * Render the trigger for the slide out trash can
     *
     * @param object $controller
     * @return string
     */
    private function repositoryTrash(object $controller): string
    {
        $html = '';
        $trashCount = $controller->repository->getRepo()->count(['deleted_at' => 1]);
        if ($this->hasTrashSupport($controller)) {
            $html = '<ul class="uk-iconnav">';
            $html .= '<li>';
            $html .= sprintf(
                '<a id="trash-can-trigger-%s" href="" uk-toggle="target: #offcanvas-flip" uk-tooltip="Open Trash" class="uk-text-danger">%s</a>', 
                $this->controllerName($controller),
                IconLibrary::getIcon('trash', 1.0),
                $trashCount = $controller->repository->getRepo()->count(['deleted_at' => 1])
            );
            $html .= '</li>';
            $html .= '<li><a uk-tooltip="Total in trash" class="uk-text-danger uk-text-meta" href="#"> (' . $trashCount . ')</a></li>';
            $html .= '</ul>';
            $html .= $this->trashCan($controller, $trashCount);
            $html .= PHP_EOL;
    
        }
        return $html;
    }
    
    /**
     * Render the slide out trash can. trash items can be restored or emptied from ths 
     * slide out panel view.
     *
     * @param object $controller
     * @param integer $trashCount
     * @return string
     */
    private function trashCan(object $controller, int $trashCount): string
    {

        $html = '<div id="offcanvas-flip" class="trash-can-' . $this->controllerName($controller) . '" uk-offcanvas="flip: true; overlay: true">';
            $html .= '<div class="uk-offcanvas-bar">';
                $html .= '<button class="uk-offcanvas-close" type="button" uk-close></button>';
                $html .= '<div class="uk-card-header">';
                    $html .= '<div class="uk-grid-small uk-flex-middle" uk-grid>';
                        $html .= '<div class="uk-width-auto">';
                            $html .= IconLibrary::getIcon('trash', 3.5);
                        $html .= '</div>';
                        $html .= '<div class="uk-width-expand">';
                        $html .= '<h3 class="uk-card-title uk-text-bolder uk-margin-remove-bottom">' . ($trashCount ?: 0) . ' Trash</h3>';
                        $html .= '<p class="uk-text-meta uk-margin-remove-top">';
                        $html .= '<span>Theres currently ' . ($trashCount ?: 0) . ' item in the trash.</span>';
                        $html .= '<hr>';
                        $html .= '</p>';
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</div>';
                if ($trashCount > 0) {
                    $html .= '<button class="uk-button uk-button-small uk-button-danger" name="emptyTrash-' . $this->controllerName($controller) . '" type="submit">Empty</button>';
                    $html .= '<button class="uk-margin-small-left uk-button uk-button-small uk-button-default" name="restoreTrash-' . $this->controllerName($controller) . '" type="submit">Restore</button>';
                } else {
                    $html .= '<h3 class="uk-text-center uk-card-title uk-text-bolder uk-margin-remove-bottom">Empty!</h3>';
                }

                $html .= '<div class="uk-panel uk-margin">';
                $html .= '<ul class="uk-list uk-list-divider uk-list-striped uk-list-collapse">';
                    $html .= $this->renderTrashLists($controller, $trashCount);
                $html .= '</ul>';
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Render the trash list
     *
     * @param object $controller
     * @param integer $trashCount
     * @return void
     */
    private function renderTrashLists(object $controller, int $trashCount)
    {
        $html = '';
        if ($trashCount > 0) {
            $items = $controller->repository->getRepo()->findBy(['id'], ['deleted_at' => 1]);
            if (count($items) > 0) {
                foreach ($items as $item) {
                    $html .= '<li>';
                        $html .= '<div class="uk-clearfix">';
                            $html .= '<div class="uk-float-left">';
                                $html .= '#' . $item['id'] . ' ';
                                $html .= sprintf('%s', $controller->repository->getnameForSelectField($item['id']));
                            $html .= '</div>';

                            $html .= '<div class="uk-float-right">';
                                $html .= sprintf('<a uk-tooltip="Restore" class="uk-link-reset" href="/admin/%s/%s/untrash">', $this->controllerName($controller), $item['id']);
                                $html .= IconLibrary::getIcon('refresh', 0.7);
                                $html .= '<input type="hidden" name="id[]" value="' . $item['id'] . '" />';
                                $html .= '</a>';

                                $html .= sprintf('<a uk-tooltip="Delete" class="uk-link-reset uk-text-danger" href="/admin/%s/%s/hard-delete">', $this->controllerName($controller), $item['id']);
                                $html .= IconLibrary::getIcon('trash', 0.7);
                                $html .= '</a>';

                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</li>';
                    $html .= PHP_EOL;
                }
            }
        }

        return $html;
    }

    private function jumpPaging(object $controller): string
    {
        $html = '';
        $html .= '<div>';
        $html .= '<select name="page_jump" class="uk-select uk-form-blank uk-border-bottom">';
        $html .= '<option>Jump</option>';
        $html .= '</select>';
        $html .= '</div>';
        $html .= $controller->tableGrid->getTotalPages();

        return $html;
    }

    /**
     * @param object $controller
     * @return string
     */
    private function getRowsPerPage(object $controller)
    {
        $tableRows = $this->getTableRows($controller);
        $html = '';
        $html .= '<div class="uk-margin-large-right uk-text-meta uk-inline">';
        $html .= '<span>Rows per page.</span>';
        $html .= '<select name="rows_per_page" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="uk-select uk-blank uk-form-width-xsmall uk-form-small">';
        $html .= sprintf('<option value="%s">%s</option>', $tableRows, $tableRows);
        $html .= $this->numberRange($controller);
        $html .= '</select>';
        $html .= '</div>';
        return $html;
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
        $sessionData = $this->getSessionData($controller->thisRouteController() . '_settings', $controller);
        $controllerTableRows = (int)$sessionData['records_per_page'];
        /* We want to return the greater value */
        if (!empty($globalTableRows) && !empty($controllerTableRows))
            return ($controllerTableRows >= $globalTableRows) ? $controllerTableRows : $globalTableRows;
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

    private function numberRange(object $controller = null)
    {
        $num = '';
        foreach (range(10, 150, 10) as $number) {
            $num .= '<option value="/admin/' . $controller->thisRouteController() . '/changeRow?rpp=' . $number . '">' . $number . '</option>';
        }
        return $num;
    }
}
