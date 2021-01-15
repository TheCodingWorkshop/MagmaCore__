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

namespace MagmaCore\Base\Traits;

use MagmaCore\Utility\Sanitizer;
use MagmaCore\Utility\Yaml;

trait TableSettingsTrait
{

    private function channel(string $controller)
    {
        return $controller . '_settings';
    }

    private function channelKey()
    {
        return "id";
    }

    private function tableOptions(string $autoController): array
    {
        $cleanData = Sanitizer::clean($_POST);
        $config = Yaml::file('controller')[$autoController];

        /* records per page */
        $recordsPerPage = (isset($cleanData['records_per_page']) ? $cleanData['records_per_page'] : $config['records_per_page']);
        /* column visibility */
        $columnVisible = (isset($cleanData['columns_visible']) ? $cleanData['columns_visible'] : []);
        /* filter by */
        $filterBy = (isset($cleanData['filter_by']) ? $cleanData['filter_by'] : $config['filter_by']);

        return [
            $recordsPerPage,
            $columnVisible,
            $filterBy
        ];
    }

    private function tableData(string $controller)
    {
        list(
            $recordsPerPage,
            $columnVisible,
            $filterBy
        ) = $this->tableOptions($controller);

        $fileData = [
            "id" => $controller,
            "records_per_page" => $recordsPerPage,
            "columns_visible" => $columnVisible,
            "filter_by" => $filterBy
        ];

        return $fileData;
    }

    /**
     * Allows each entity to be able to be customizable in terms of settings the amount
     * of table data to be displayd per page and change the search filter. These options 
     * can be customize from each entity page.
     *
     * @param string $controller
     * @return boolean
     */
    public function tableSettingsInit(string $controller): bool
    {
        if ($this->isControllerValid($controller)) {
            $fileData = $this->tableData($controller);
            $this->flatDb
                ->flatDatabase()
                ->insert()
                ->in($this->channel($controller))
                ->set($fileData)
                ->execute();
            return true;
        }
        return false;
    }

    /**
     * Method which updates the entity page settings
     *
     * @param string $controller
     * @return void
     */
    public function tableSettingsUpdateInit(string $controller)
    {
        if ($this->isControllerValid($controller)) {
            $fileData = $this->tableData($controller);
            $this->flatDb
                ->flatDatabase()
                ->update()
                ->in($this->channel($controller))
                ->set($fileData)
                ->execute();
            return true;
        }
        return false;
    }

    public function tableSettings(string $controller, string $settingName)
    {
        $settings = $this->flatDb
            ->flatDatabase()
            ->read()
            ->in($controller . "_settings")
            ->where("id", "==", $controller)
            ->get();
        $option = [];
        if ($settings) {
            foreach ($settings as $setting) {
                $option[] = $settings;
            }
            return (isset($option[$settingName]) ? $option[$settingName] : Yaml::file('controller')[$controller][$settingName]);
        }
        return false;
    }
}
