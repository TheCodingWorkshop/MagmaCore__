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
use MagmaCore\Administrator\ControllerSettingsModel;
use MagmaCore\DataObjectLayer\ClientRepository\ClientRepository;
use MagmaCore\DataObjectLayer\ClientRepository\ClientRepositoryFactory;

trait ControllerSettingTrait
{

    /**
     * Undocumented function
     *
     * @param array $routeParams
     * @return boolean
     */
    public function buildController(array $routeParams): bool
    {

        if (count($routeParams)) {
    
            $disallowedControllers = Yaml::file('app')['disallowed_controllers'];
            if (!in_array($routeParams['controller'], $disallowedControllers)) {
    
                $controller = $this->getController(['controller_name' => $routeParams['controller']]);
                //if (!isset($controller)) {
    
                    $menuID = $this->getControllerMenu(['controller_name' => $routeParams['controller']]);
                    var_dump($menuID);
                    die;

                    $args = Yaml::file('controller')[$controller];
                    $props = [
                        'controller_menu_id' => (int)$menuID['id'],
                        'controller_name' => $routeParams['controller'],
                        'records_per_page' => $args['records_per_page'],
                        'additional_conditions' => Serializer::compress($args['additional_conditions']),
                        'selectors' => Serializer::compress($args['selectors']),
                        'visibility' => Serializer::compress($args['visibility']),
                        'sortable' => Serializer::compress($args['sort_columns']),
                        'searchable' => Serializer::compress($args['searchables']),
                        'query_values' => '',
                        'query' => $args['query'],
                        'alias' => $args['filter_alias'],
                        'filter' => Serializer::compress($args['filter_by'])
                    ];
                    return (new ControllerSettingsModel())->getRepo()->getEm()->getCrud()->create($props);
                    // $new = $this->getControllerModel()->save($props, null);
                    // if ($new) {
                    //     return true;
                    // }
               // }
            }
        }
        return false;
    }

    /**
     * Return the client repository object
     *
     * @return ClientRepository
     */
    private function getControllerModel(): ClientRepository
    {
        return (new ClientRepositoryFactory(
            'controller_model', 
            'controller_settings', 
            'id'))->create(ClientRepository::class);
    }

    /**
     * Get a controller database row based on the method argument
     *
     * @param array $conditions
     * @return array|null
     */
    private function getController(array $conditions): ?array
    {
        $model = $this->getControllerModel();
        if ($model !==null) {
            return $model->get($conditions);
        }

        return null;

    }

}