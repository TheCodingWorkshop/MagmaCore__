<?php

namespace MagmaCore\Traits;

use MagmaCore\Base\BaseApplication;

trait ConfigurationTraits
{

    public function isOverridingConfig(array $config): array
    {

        return $config;
    }

    /**
     * @param array $dependencies
     * @return array
     */
    public function registerConfigBundlerDependencies(array $dependencies = []): array
    {
        if (count($dependencies) > 0) {
            foreach ($dependencies as $key => $dependency) {
                if (!class_exists($dependency)) {
                    throw new BundlerInvalidArgumentException();
                }
                $this->$key = BaseApplication::diGet($dependency);
            }
        }

    }

}