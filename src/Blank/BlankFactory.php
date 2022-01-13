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

use MagmaCore\Bundler\BundlerTrait;
use MagmaCore\Collection\Collection;
use MagmaCore\Collection\CollectionInterface;
use MagmaCore\Contracts\FactoryInterface;
use MagmaCore\Contracts\ConfigurationInterface;

class BlankFactory implements FactoryInterface
{

    use BlankTrait;
    use BundlerTrait;
    private CollectionInterface $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Responsible for creating an instance of the Blank object
     *
     * @param string|null $driver
     * @param array $overridingConfig
     * @return Blank
     */
    public function create(?string $driver = null, array $overridingConfig = [], mixed $optionalParameter = null): BlankInterface
    {
        $optionalParamResolved = $this->resolveOptionalParameter($optionalParameter);
        $configObject = new BlankConfigurator(BlankConfigurations::configurations(), $overridingConfig);
        $validConfigObject = (!$configObject instanceof ConfigurationInterface) ? throw new ConfiguratonInvalidArgumentException() : $configObject;

        $newDriver = $this->theDriver($driver, $validConfigObject, $optionalParamResolved);
        $concreteObject = new Blank($newDriver, $optionalParamResolved);
        $options = $validConfigObject->getParentConfig();

        $this->makeObjectGlobal($concreteObject, $options);
        return $concreteObject;

    }


}