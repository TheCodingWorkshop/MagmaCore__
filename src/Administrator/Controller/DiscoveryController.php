<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types=1);

namespace MagmaCore\Administrator\Controller;

use MagmaCore\Administrator\Model\ControllerDbModel;
use MagmaCore\Base\Domain\Actions\DiscoverAction;
use MagmaCore\Utility\Serializer;
use MagmaCore\Utility\Utilities;

class DiscoveryController extends AdminController
{

    /**
     * @param array $routeParams
     */
    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        $this->addDefinitions(
            [
                'repository' => ControllerDbModel::class,
                'discoverAction' => DiscoverAction::class,
            ]
        );
    }

    /**
     * Discover new controller through system sessions
     */
    protected function discoverAction()
    {
        $repository = $this->repository;
        $singular = $this->repository->getRepo()->findObjectBy(['id' => (int)$_GET['edit']]);
        $this->discoverAction
            ->execute($this, NULL, NULL, NULL, __METHOD__)
            ->render()
            ->with(
                [
                    'controllers' => $this->repository->getRepo()->findAll(),
                    'controller_singular' => $singular,
                    'methods' => Serializer::unCompress($singular->methods),
                    'session' => $this->getSession(),
                    'session_discovery' => $this->getSession()->get('controller_discovery'),
                ]
            )
            ->callback(fn($controller) => '')
            ->end();
    }

}
