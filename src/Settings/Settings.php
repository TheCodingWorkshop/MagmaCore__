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

namespace MagmaCore\Settings;

use App\Model\SettingModel;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

if (class_exists(SettingModel)) {
    throw new MissingSettngModelException();
}

class Settings
{

    /**
     * @var object
     */
    protected object $model;

    /**
     * Main constructor class
     */
    public function __construct(SettingModel $model)
    {
        $this->model = $model;
    }

    public function get(string $name)
    {
        if (empty($name)) {
            throw new BaseInvalidArgumentException('What setting are you trying to retrieve. The key is required.');
        }

        $find = $this->model->getRepo()->findObjectBy(['setting_name' => $name]);
        if ($find) {
            return $find->setting_value;
        }
    }

    /**
     * Method will ensure if current $value is == to the $oldValue then return false
     * but if the new $value doesn't not equal to the $oldValue then add the new key
     * and value to schema table.
     */
    public function set(string $name, $value): bool
    {

        if (empty($name)) {
            throw new BaseInvalidArgumentException('You cannot set a value without a key.');
        }

        if (is_string($value))
            $value = trim($value);

        // If the value is the same as the old value do nothing
        $oldValue = $this->get($name);
        if ($value == $oldValue)
            return false;

        // Add the value if its different  from the existing value
        if (false === $oldValue) {
            $this->add($name, $value);
            return true;
        }
        $set = $this->model->getRepo()->getEm()->getCrud()->update(['setting_value' => $value, 'setting_name' => $name], 'setting_name');
        if ($set)
            return true;
    }

    public function add(string $name, $value): bool
    {
        if (empty($name)) {
            throw new BaseInvalidArgumentException('A key name is required in order to add an option.');
        }
        $add = $this->model->getRepo()->getEm()->getCrud()->create(['setting_name' => $name, 'setting_value' => $value]);
        if ($add) {
            return true;
        }
    }

    public function delete(string $name): bool
    {
        if (empty($name)) {
            throw new BaseInvalidArgumentException('Setting name is required to be able to remove this option.');
        }

        $delete = $this->model->getRepo()->getEm()->getCrud()->delete(['setting_name' => $name]);
        if ($delete) {
            return true;
        }
    }
}
