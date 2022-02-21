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

use MagmaCore\Utility\Serializer;

trait ControllerDiscoveryTrait
{

    /**
     * Discover new controller by gatehering the parameters for the controller and inserting it
     * within the database.
     *
     * @param object $model
     */
    public function discoverNewController(object $model = null, ?string $controllerName = null, object $session = null, ?string $sessionKey = null): bool
    {
        if (!empty($controllerName)) {
            $fields = ['controller' => $controllerName, 'methods' => Serializer::compress($this->getActionMethods()), 'active' => 0];
            if ($session->has($sessionKey)) {
                $session->set($sessionKey, $fields);
            }
            return $model
                ->getEm()
                ->getCrud()
                ->create($fields);
        }

        return false;
    }

}