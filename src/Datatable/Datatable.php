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

namespace MagmaCore\Datatable;

use MagmaCore\Datatable\Exception\DatatableUnexpectedValueException;
use MagmaCore\Datatable\AbstractDatatable;
use MagmaCore\Twig\TwigExtension;
use MagmaCore\Http\RequestHandler;

class Datatable extends AbstractDatatable
{

    protected string $element = '';

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function create(string $dataColumnString, array $dataRepository = [], array $sortController = []) : self
    {
        $this->dataColumnObject = new $dataColumnString();
        if (!$this->dataColumnObject instanceof DatatableColumnInterface) {
            throw new DatatableUnexpectedValueException($dataColumnString . ' is not a valid data column object.');
        }
        $this->dataColumns = $this->dataColumnObject->columns();
        $this->sortController = $sortController;
        $this->getRepositoryParts($dataRepository);
        return $this;

    }

    private function getRepositoryParts(array $dataRepository) : void
    {
        list(
            $this->dataOptions, 
            $this->currentPage, 
            $this->totalPages, 
            $this->totalRecords, 
            $this->direction, 
            $this->sortDirection, 
            $this->tdClass, 
            $this->tableColumn, 
            $this->tableOrder) = $dataRepository;
    }

    public function totalRecords()
    {
        return $this->totalRecords;
    }

    public function getColumns()
    {
        return $this->tableColumn;
    }

    public function table() : ?string
    {
        extract($this->attr, EXTR_SKIP);
        $status = (new RequestHandler())
        ->handler()
        ->query
        ->getAlnum($this->sortController['query']);

        $this->element .= $before;
        if (is_array($this->dataColumns) && count($this->dataColumns) > 0) {
            if (is_array($this->dataOptions) && $this->dataOptions !=null) {
                $this->element .= '<table id="' . (isset($table_id) ? $table_id : '') . '" class="'. implode(' ', $table_class) .'">' . "\n";
                    $this->element .= ($show_table_thead) ? $this->tableGridElements($status) : false;
                    $this->element .= '<tbody>' . "\n";
                        foreach ($this->dataOptions as $row) {
                            $this->element .= '<tr>' . "\n";
                                foreach ($this->dataColumns as $column) {
                                    if (isset($column['show_column']) && $column['show_column'] != false) {
                                        $this->element .= '<td class="' . $column['class'] . '">';
                                            if (is_callable($column['formatter'])) {
                                                $this->element .= call_user_func_array($column['formatter'], [$row, (new TwigExtension())]);
                                            } else {
                                                $this->element .= (isset($row[$column['db_row']]) ? $row[$column['db_row']] : '');
                                            }
                                        $this->element .= '</td>' . "\n";
                                    }
                                }
                            $this->element .= '</tr>' . "\n";
                        }
                    $this->element .= '</tbody>' . "\n";
                    //$this->element .= ($show_table_tfoot) ? $this->tableGridElements($status, true) : '';
                $this->element .= '</table>' . "\n";
            }
        }
        $this->element .= $after;

        return $this->element;
        
    }

    protected function tableGridElements(string $status, bool $inFoot = false) : string
    {
        $element = sprintf('<%s>', ($inFoot) ? 'tfoot' : 'thead');
            $element .= '<tr>';
                foreach ($this->dataColumns as $column) {
                    if (isset($column['show_column']) && $column['show_column'] != false) {
                        $element .= '<th>';
                        $element .= $this->tableSorting($column, $status);
                        $element .= '</th>';
                    }
                }
            $element .= '</tr>';
        $element .= sprintf('</%s>', ($inFoot) ? 'tfoot' : 'thead');

        return $element;
    }

    private function tableSorting(array $column, string $status) : string
    {
        $element = '';
        if (isset($column['sortable']) && $column['sortable'] != false) {
            $element .= '<a class="uk-link-reset" href="' . ($status ? '?status=' . $status . '&column=' . $column['db_row'] . '&order=' . $this->sortDirection . '' : '?column=' . $column['db_row'] . '&order=' . $this->sortDirection . '') . '">';

            $element .= $column['dt_row'];

            $element .= '<i class="fas fa-sort' . ($this->tableColumn == $column['db_row'] ? '-' . $this->direction : '') . '"></i>';

            $element .= '</a>';
            
        } else {
            $element .= $column['dt_row'];
        }
        return $element;
    }

    /**
     * Return the table pagination. Pagination will work in conjunction with 
     * return query and will attemp to display the correct information based on
     * that query
     * 
     * @return string
     */
    public function pagination() : string
    {
        extract($this->attr);

        $element = '<section class="uk-margin-medium-top uk-padding-small uk-padding-remove-bottom">';
        $element .= '<nav aria-label="Pagination" uk-navbar>';

        /**
         * table meta information
         */
        $element .= '<div class="uk-navbar-left" style="margin-top: -15px;">';
        $element .= sprintf(
            '&nbsp;Showing&nbsp<span>%s</span> - <span>%s</span>&nbsp; of &nbsp;<span>%s</span>&nbsp;',
            $this->currentPage,
            $this->totalPages,
            $this->totalRecords
        );
        $element .= '<span class="uk-text-meta uk-text-warning uk-margin-small-left"></span>';
        $element .= '<form oninput="result.value=parseInt(a.value)">';
            $element .= '<input onchange="saveChanges(this);" type="range" id="a" name="a" value="50" />';
            $element .= '<span style="margin-top: -13px;" class="uk-badge uk-badge-primary"><output name="result" for="b">' . $this->sortController['records_per_page'] . '</output></span>';
        $element .= '</form>';
        $element .= '</div>';

        $queryStatus = ($this->sortController['query'] ? $this->sortController['query'] : '');
        $status = (isset($_GET[$queryStatus]) ? $_GET[$queryStatus] : '');

        /**
         * pagination simple or numbers 
         */
        $element .= '<div class="uk-navbar-right">';
        $element .= '<ul class="uk-pagination">';

        $element .= '<li class="' . ($this->currentPage == 1 ? 'uk-disabled' : 'uk-active') . '">';

        if ($this->currentPage == 1) {
            $element .= sprintf(
                '<a href="%s"><span class="uk-margin-small-right" uk-pagination-previous></span> Previous</a>',
                'javascript:void(0);'
            );
        } else {
            if ($status) {
                $element .= sprintf(
                    '<a href="?' . $queryStatus . '=%s&page=%s">',
                    $status,
                    ($this->currentPage - 1)
                );
            } else {
                $element .= sprintf(
                    '<a href="?page=%s">',
                    ($this->currentPage - 1)
                );
            }
            $element .= '<span class="uk-margin-small-right" uk-pagination-previous></span> Previous</a>';

        }
        $element .= '</li>';

        /** NEXT */
        $element .= '<li class="uk-margin-auto-left ' . ($this->currentPage == $this->totalPages ? 'uk-disabled' : 'uk-active') . '">';
        if ($this->currentPage == $this->totalPages) {
            $element .= sprintf(
                '<a href="%s">Next <span class="uk-margin-small-left" uk-pagination-next></span>',
                'javascript:void(0);'
            );
        } else {

            if ($status) {
                $element .= sprintf(
                    '<a href="?' . $queryStatus . '=%s&page=%s">',
                    $status,
                    ($this->currentPage + 1)
                );
            } else {
                $element .= sprintf(
                    '<a href="?page=%s">',
                    ($this->currentPage + 1)
                );
            }
            $element .= 'Next <span class="uk-margin-small-left" uk-pagination-next></span></a>';
        }
        $element .= '</li>';

        $element .= '</ul>';
        $element .= '</div>';

        $element .= '</nav>';
        $element .= '</section>';

        return $element;
    }


}