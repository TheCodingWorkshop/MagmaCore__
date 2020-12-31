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
        list($this->dataOptions, $this->currentPage, $this->totalPages, $this->totalRecords, $this->direction, $this->sortDirection, $this->tdClass, $this->tableColumn, $this->tableOrder) = $dataRepository;
    }

    public function table() : ?string
    {
        extract($this->attr, EXTR_SKIP);
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
                                                $this->element .= call_user_func_array($column['formatter'], [$row]);
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

    public function pagination() : ?string
    {return '';}


}