<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MagmaCore\Blank;

use MagmaCore\Contracts\ConfigurationInterface;
use MagmaCore\Traits\ConfigurationTraits;

class BlankConfigurator implements ConfigurationInterface
{

    use ConfigurationTraits;

    private array $overridingConfig = [];
    private array $configurations;

    /**
     * @param array $configurations
     * @param array $overridingConfig
     */
    public function __construct(array $configurations = [], array $overridingConfig =[])
    {
        $this->configurations = $configurations;
        $this->overridingConfig = $overridingConfig;
    }

    /**
     * Allow overriding of the base configurations from an application level. array must match
     * the base configuration array
     *
     * @return array
     */
    public function getParentConfig(): array
    {
        return array_merge($this->configurations, $this->overridingConfig);
    }

    /**
     * Returns the bundler configurations array
     * @return array
     */
    public function getConfigBundler(): array
    {
        return $this->getParentConfig()['bundler'];
    }


}