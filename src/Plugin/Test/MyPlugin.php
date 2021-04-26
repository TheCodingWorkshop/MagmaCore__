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

namespace MagmaCore\Plugin;

use MagmaCore\Plugin\PluginBuilderInterface;

class MyPlugin implements PluginBuilderInterface
{

    protected const TABLEsCHEMA = 'my_plugin';
    protected const TABLESCHEMAID = 'id';

    public function __construct(array $services)
    {
        list($this->repository, $this->session, $this->cache) = $services;
    }

    public function myPluginSchema()
    {
        
    }


    /**
     * Execute the plugin
     *
     * @return string
     */
    public function pluginProcessor(): mixed
    {}

}
