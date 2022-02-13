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

use Exception;
use MagmaCore\IconLibrary;
use MagmaCore\Utility\Stringify;

trait DataColumnTrait
{

    /**
     * @param array $row
     * @return string
     */
    private function getDropdownStatus(array $row): string
    {
        $stat = '';
        if ($row['status'] === 'pending') {
            $stat = 'Not Activated';
        } elseif ($row['status'] === 'active') {
            $stat = 'account active';
        }

        return $stat;
    }


    /**
     * Return the generated path for the the current routes array defined
     *
     * @param array $row
     * @param string|null $path
     * @return string
     */
    private function adminPath(array $row, string $controller, ?string $path = null): string
    {
        if ($path !== null) {
            return "/admin/{$controller}/{$row['id']}/{$path}";
        } else {
            return "/admin/{$controller}/index";
        }
    }

    /**
     * @param array $row
     */
    private function getRole(array $row)
    {}

    /**
     * Return icon representation of the various column status
     *
     * @param object $controller
     * @param array $row
     * @return string
     * @throws Exception
     */
    public function displayStatus(object $controller, array $row): string
    {
        return $this->getStatusValues($controller, callback: function ($key, $value) use ($row) {
            if (!in_array($row[$key], $value)) {
                throw new Exception($row[$key] . ' is not a value specified within your model.');
            }
            $colors = ['warning', 'success', 'danger', 'secondary', ''];
            $count = 0;
            foreach ($value as $k => $val) {
                $ret = '';
                switch ($row[$key]) {
                    case $val:
                        $icon = '';
                        $icon = match ($val) {
                            'pending' => 'warning',
                            'active' => 'check',
                            'trash' => 'trash',
                            'lock' => 'lock',
                            '' => 'question'
                        };
                        $ret = '<span class="uk-text-' . $colors[$k] . '" uk-tooltip="' . Stringify::capitalize($val) . '">' . IconLibrary::getIcon($icon, 0.7) . '</span>';
                        $count++;

                        return $ret;

                        break;
                }
            }
        });
    }

    /**
     * Truncate large string to the desired length
     *
     * @param string $str
     * @param integer $max
     * @param integer $min
     * @return void
     */
    public function truncate(string $str, int $max = 100, int $min = 80)
    {
        if (strlen($str) > $max)
            $str = substr($str, 0, $min) . ' ...';

        return $str;
    }

    /**
     * Return the 2 most common links within each data table row
     *
     * @return array
     */
    public function columnBasicLinks(object|string $class = null, array $row = []): array
    {
        $basics = [
            'edit' => ['name' => 'edit', 'icon' => 'pencil'],
            'trash' => ['name' => 'trash (not permanent)', 'icon' => 'trash']
        ];

        if (method_exists($class, 'moreLinks')) {
            return array_merge($basics, $this->moreLinks($row));
        } else {
            return $basics;
        }

    }

    /**
     * Loop through the lists of items provided for each data table row
     *
     * @param array $row
     * @param array $items
     * @param string|null $controller
     * @return array
     */
    public function filterColumnActions(array $row = [], array $items = [], ?string $controller = null): array
    {
        return array_map(
            fn($key, $value) => array_merge(['path' => $this->adminPath($row, $controller, $key)], $value),
            array_keys($items),
            $items
        );

    }


}
