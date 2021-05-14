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

use MagmaCore\Themes\Uikit\Uikit;
use MagmaCore\Twig\TwigExtension;
use MagmaCore\Http\RequestHandler;
use MagmaCore\Themes\ThemeBuilder;
use MagmaCore\Datatable\AbstractDatatable;
use MagmaCore\Datatable\Exception\DatatableUnexpectedValueException;

class Datatable extends AbstractDatatable
{

    protected string $element = '';
    protected object $tb;

    private int|false $currentPage = false;
    private int|false $totalPages = false;
    private int|false $totalRecords = false;
    private array $dataColumns = [];

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(ThemeBuilder $themeBuilder)
    {
        $this->tb = $themeBuilder->create(Uikit::class);
        parent::__construct();
    }

    /**
     * Undocumented function
     *
     * @param string $dataColumnString
     * @param array $dataRepository
     * @param array $sortController
     * @param array $dbColumns
     * @return self
     */
    public function create(string $dataColumnString, array $dataRepository = [], array $sortController = [], array $dbColumns = [], object|null $callingController = null): self
    {
        $this->dataColumnObject = new $dataColumnString();
        if (!$this->dataColumnObject instanceof DatatableColumnInterface) {
            throw new DatatableUnexpectedValueException($dataColumnString . ' is not a valid data column object.');
        }
        $this->dataColumns = $this->dataColumnObject->columns($dbColumns, $callingController);
        $this->sortController = $sortController;
        $this->getRepositoryParts($dataRepository);
        return $this;
    }

    private function getRepositoryParts(array $dataRepository): void
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
            $this->tableOrder
        ) = $dataRepository;
    }

    public function totalRecords()
    {
        return $this->totalRecords;
    }

    public function getColumns()
    {
        return $this->tableColumn;
    }

    public function table(): null|string
    {
        extract($this->attr, EXTR_SKIP);
        $status = (new RequestHandler())
            ->handler()
            ->query
            ->getAlnum($this->sortController['query']);

        $this->element .= $before;
        if (is_array($this->dataColumns) && count($this->dataColumns) > 0) {
            if (is_array($this->dataOptions) && $this->dataOptions != null) {
                $this->element .= '<table id="' . (isset($table_id) ? $table_id : '') . '" class="' . implode(' ', $this->tb->theme('table_class')) . '">' . "\n";
                $this->element .= ($show_table_thead) ? $this->tableGridElements($status) : false;
                $this->element .= '<tbody>' . "\n";
                foreach ($this->dataOptions as $row) {
                    $this->element .= '<tr>' . "\n";
                    foreach ($this->dataColumns as $column) {
                        if (isset($column['show_column']) && $column['show_column'] != false) {
                            $this->element .= '<td id="toggle-' . $column['db_row'] . '" class="' . ($this->tableColumn == $column['db_row'] ? $this->tdClass : '') . ' ' . $column['class'] . '">';
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

    protected function tableGridElements(string $status, bool $inFoot = false): string
    {
        $element = sprintf('<%s>', ($inFoot) ? 'tfoot' : 'thead');
        $element .= '<tr>';
        foreach ($this->dataColumns as $column) {
            if (isset($column['show_column']) && $column['show_column'] != false) {
                $element .= '<th id="toggle-' . $column['db_row'] . '">';
                $element .= $this->tableSorting($column, $status);
                $element .= '</th>';
            }
        }
        $element .= '</tr>';
        $element .= sprintf('</%s>', ($inFoot) ? 'tfoot' : 'thead');

        return $element;
    }

    /**
     * Return the table sorting classes and arrow directions
     *
     * @param array $column
     * @param string $status
     * @return string
     */
    private function tableSorting(array $column, string $status): string
    {
        $element = '';
        if (isset($column['sortable']) && $column['sortable'] != false) {
            $element .= '<a class="' . $this->tb->theme('table_reset_link') . '" href="' . ($status ? '?status=' . $status . '&column=' . $column['db_row'] . '&order=' . $this->sortDirection . '' : '?column=' . $column['db_row'] . '&order=' . $this->sortDirection . '') . '">';

            $element .= $column['dt_row'];

            $element .= '<i class="fas fa-sort' . ($this->tableColumn == $column['db_row'] ? '-' . $this->direction : '') . '"></i>';

            $element .= '</a>';
        } else {
            $element .= $column['dt_row'];
        }
        return $element;
    }

    /**
     * Returns the previous pagination link
     *
     * @param string $status
     * @param mixed $queryStatus
     * @return string
     */
    public function previousPaging(string $status, mixed $queryStatus): string
    {
        $element = '';
        $element .= '<li class="' . ($this->currentPage == 1 ? $this->tb->theme('paging')['disable'] : $this->tb->theme('paging')['active']) . '">';
        if ($this->currentPage == 1) {
            $element .= sprintf(
                '<a href="%s">',
                'javascript:void()'
            );
        } elseif ($status) {
            $element .= sprintf(
                '<a href="?' . $queryStatus . '=%s&page=%s">',
                $status,
                ($this->currentPage - 1)
            );
        } else {
            $element .= sprintf('<a href="?page=%s">', ($this->currentPage - 1));
        }
        $element .= '<span>Previous</span></a>' . PHP_EOL;
        $element .= '</li>' . PHP_EOL;

        return $element;
    }

    /**
     * Returns the next pagination link
     *
     * @param string $status
     * @param mixed $queryStatus
     * @return string
     */
    public function nextPaging(string $status, mixed $queryStatus): string
    {
        $element = '';
        $element .= '<li class="' . ($this->currentPage == $this->totalPages ? $this->tb->theme('paging')['disable'] : $this->tb->theme('paging')['active']) . '">';
        if ($this->currentPage == $this->totalPages) {
            $element .= sprintf(
                '<a href="%s">',
                'javascript:void()'
            );
        } elseif ($status) {
            $element .= sprintf(
                '<a href="?' . $queryStatus . '=%s&page=%s">',
                $status,
                ($this->currentPage + 1)
            );
        } else {
            $element .= sprintf('<a href="?page=%s">', ($this->currentPage + 1));
        }
        $element .= '<span>Next</span></a>' . PHP_EOL;
        $element .= '</li>' . PHP_EOL;

        return $element;
    }

    /**
     * Get the queried string set within the controllers configuration file
     *
     * @return string
     */
    public function getQueriedStatus(): string
    {
        return (isset($this->sortController['query']) ? $this->sortController['query'] : '');
    }

    /**
     * Get the queried status
     *
     * @return mixed
     */
    public function getStatus(): mixed
    {
        $statusQueried = $this->getQueriedStatus();
        return (isset($_GET[$statusQueried]) ? $_GET[$statusQueried] : '');
    }

    /**
     * Return the current queried page number
     *
     * @return integer|false
     */
    public function getCurrentPage(): int|false
    {
        return (isset($this->currentPage) ? $this->currentPage : false);
    }

    /**
     * Return the total filtered pages for the queried model
     *
     * @return integer|false
     */
    public function getTotalPages(): int|false
    {
        return (isset($this->totalPages) ? $this->totalPages : false);
    }

    /**
     * Return the total records for the queried model
     *
     * @return integer|false
     */
    public function getTotalRecords(): int|false
    {
        return (isset($this->totalRecords) ? $this->totalRecords : false);
    }

    public function pagination(): string
    {
        return '';
    }

    /**
     * Return an array of the relevant data columns
     *
     * @return array
     */
    public function getDataColumns(): array
    {
        return $this->dataColumns;
    }
}
