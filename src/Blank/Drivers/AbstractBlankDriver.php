<?php

namespace MagmaCore\Blank\Drivers;

abstract class AbstractBlankDriver implements BlankDriverInterface
{

    private object $overridingConfig;

    /**
     * We will use the optional parameter within this component to access the dotenv library
     *
     * @param object $overridingConfig
     * @param mixed|null $optionalParameters
     */
    public function __construct(object $overridingConfig, mixed $optionalParameters = null)
    {
        $this->overridingConfig = $overridingConfig;
        $this->optionalParameters = $optionalParameters;
    }

    /**
     * Return the cache configurations parent configs
     * @return object
     */
    public function getConfig(): object
    {
        return $this->overridingConfig->getParentConfig();
    }

    private function getBundlerOptions(): array
    {
        $options = $this->getConfig();
        return array_key_exists('bundler', $options) ? $options['bundler']['options'] : [];
    }

}