<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MagmaCore\Settings;;

use MagmaCore\Base\BaseModel;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Utility\Yaml;

class SettingModel extends BaseModel
{

    /** @var string */
    protected const TABLESCHEMA = 'settings';
    /** @var string */
    protected const TABLESCHEMAID = 'id';

    /**
     * Main constructor class which passes the relevant information to the
     * base model parent constructor. This allows the repository to fetch the
     * correct information from the database based on the model/entity
     *
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function __construct()
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, SettingEntity::class);
    }

    /**
     * Guard these IDs from being deleted etc..
     *
     * @return array
     */
    public function guardedID(): array
    {
        return [];
    }

    public function getSystemReport(): array
    {
        $config = Yaml::file('app');
        return [
            'core' => [
                'version' => $config['app']['core_version'],
                'db_version' => $config['app']['core_db_version'],
                'db_driver' => $config['database']['default_driver'],
                'memory_limit' => '2mb',
                'debug_mode' => $config['debug_error']['mode'],
                'default_language' => $config['settings']['default_locale'],
                'timezone' => $config['settings']['default_timezone']
            ],
            'php' => [
                'version' => phpversion(),
                'memory_limit' => ini_get('memory_limit'),
                'max_upload_size' => ini_get('post_max_size'),
                'max_input_vars' => ini_get('max_input_vars'),
                'time_limit' => ini_get('max_execution_time'),
                'gd_support' => (function_exists('gd_info')) ? 'Yes' : 'No',
                'mysql_version' => '5.3.2',
                'web_server_info' => $_SERVER['SERVER_SOFTWARE'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'pgsql_version' => 'Not Available',
                'sqlite_version' => 'Not Available'
            ]
        ];
    }

}