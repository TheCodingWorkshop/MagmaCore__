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

interface PluginManagerInterface
{

    /**
     * Register the name of the plugin
     *
     * @return string
     */
    public function pluginName(): string;

    /**
     * Add a meta description which briefly describes the purpose of the plugin
     *
     * @return string
     */
    public function plugDescription(): string;

    /**
     * Declare the current stabler version of the plugin
     *
     * @return integer
     */
    public function pluginVersion(): int;

    /**
     * Returns an array of author meta data. ie authors name, email address and 
     * Ensure the array is define in the correct order
     * ['name', 'email', 'etc']
     *
     * @return array
     */
    public function pluginAuthor(): array;


}