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
    private function adminPath(array $row, ?string $path = null): string
    {
        if ($path !== null) {
            return "/admin/user/{$row['id']}/{$path}";
        } else {
            return "/admin/user/index";
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
                            'pending' => 'alert-circle-outline',
                            'active' => 'checkmark-outline',
                            'trash' => 'trash-outline',
                            'lock' => 'lock-closed-outline',
                            '' => 'help-outline'
                        };
                        $ret = '<span class="uk-text-' . $colors[$k] . '" uk-tooltip="' . Stringify::capitalize($val) . '"><ion-icon name="' . $icon . '"></ion-icon></span>';
                        $count++;

                        return $ret;

                        break;
                }
            }
        });
    }

    /**
     * Undocumented function
     *
     * @param array $row
     * @return array
     */
    private function itemsDropdown(array $row): array
    {
        $items = [
            'edit' => ['name' => 'edit', 'icon' => 'create-outline'],
            'privilege' => ['name' => 'Edit Privilege', 'icon' => 'key-outline'],
            'preferences' => ['name' => 'Edit Preferences', 'icon' => 'options-outline'],
            'show' => ['name' => 'show', 'icon' => 'eye-outline'],
            'clone' => ['name' => 'clone', 'icon' => 'copy-outline'],
            'lock' => ['name' => 'lock account', 'icon' => 'lock-closed-outline'],
            'trash' => ['name' => 'trash account', 'icon' => 'trash-bin-outline']
        ];
        return array_map(
            fn($key, $value) => array_merge(['path' => $this->adminPath($row, $key)], $value),
            array_keys($items),
            $items
        );
    }

}
