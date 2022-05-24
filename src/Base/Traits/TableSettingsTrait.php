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

use MagmaCore\Utility\Yaml;
use MagmaCore\Utility\Serializer;

/**
 * Handles registering a session array for each controller on first initialization. This array contains valible data 
 * which can alter and adjust the controller behaviour. ie we can alter the base query which renders the data table on the
 * index route. We can assign any data table to be sortable etc..
 * The base parameters for the session array is define within a .yml config file and acts as a fallback if the session data fails
 * 
 * This file is executed if a new route is visited which isn't already registered
 */

trait TableSettingsTrait
{

    /**
     * The available session data. Default information is retrived from the controller.yml 
     * under the current controller key
     *
     * @param string|null $channel
     * @param array $args
     * @param object|null $controller
     * @return void
     */
    public function initialSessionData(string $channel = null, array $args = [], object $controller = null)
    {
        return [
            'channel_name' => $channel,
            'controller' => $controller->thisRouteController(),
            'records_per_page' => (string)$args['records_per_page'] ?? "15",
            'additional_conditions' => $args['additional_conditions'] ?? [],
            'selectors' => $args['selectors'] ?? [],
            'query' => $args['query'] ?? '',
            'filter_by' => $args['filter_by'] ?? [],
            'filter_alias' => $args['filter_alias'] ?? '',
            'sort_columns' => $args['sort_columns'] ?? [],
            'trash_can_support' => $args['trash_can_support'] ?? 'false',
            'paging_top' => 'true',
            'paging_bottom' => 'true',
            'bulk_clone' => 'false',
            'bulk_trash' => 'true',
            'trash_can' => 'false',
            'advance_table' => 'false'
    
        ];

    }

    /**
     * Initial session data which gets populated for each controller section within
     * the system. The session data is compress using serializer. In order to use this
     * data it must be unserialize.
     *
     * @param object|null $controller
     * @return void
     */
    public function initalizeControllerSession(object $controller = null)
    {
        $session = $this->getSession();
        /* if the session isn't set then set it */
        if (!$session->has($channel = $this->thisRouteController() . '_settings')) {
            $args = Yaml::file('controller')[$this->thisRouteController()];
            $data = $this->initialSessionData($channel, $args ?? [], $controller);
            /* set the session */
            $session->set($channel, Serializer::compress($data));
        }
    }

}
