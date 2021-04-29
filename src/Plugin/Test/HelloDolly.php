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

namespace MagmaCore\Plugin\Test;

use MagmaCore\Plugin\PluginFactory;
use MagmaCore\Plugin\Test\MyPlugin;
use MagmaCore\Plugin\PluginManagerInterface;

class HelloDolly implements PluginManagerInterface
{

    /**
     * Name: HelloDolly;
     * URI: www.wordpress.org/plugins/hello-dolly/;
     * Description: This is not just a plugin it symbolizes the hope and enthusiasm of an entire; 
     * Author: Matt Mullenweg;
     * Homepage: www.ma.tt/;
     * Version: 1.0.0
     */
    public function pluginDeploy()
    {
        $factory = new PluginFactory();
        if ($factory) {
            $factory->create(HelloDolly::class);
            $factory->registerForServices(MyPlugin::class, ['error', 'clientRepository']);
            if ($factory->hasServices()) {
                return $factory->run();
            }
        }
    }
}
