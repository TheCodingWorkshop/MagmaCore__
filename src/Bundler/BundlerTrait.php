<?php

namespace MagmaCore\Bundler;

use function count;
use function implode;
use function in_array;
use function array_keys;
use function array_key_exists;
use MagmaCore\Base\BaseApplication;
use MagmaCore\Session\GlobalManager\GlobalManager;
use MagmaCore\Bundler\Exception\BundlerDriverInvalidArgumentException;

trait BundlerTrait
{

    /**
     * Returns the string qualified namespace based on the default driver selected. Selecting an
     * invalid driver will result in an exception being thrown
     *
     * @param object $validConfigObject
     * @param mixed $optionalParamResolved
     * @return mixed
     * @throws BundlerDriverInvalidArgumentException
     */
    public function resolveDefaultDriver(object $validConfigObject, mixed $optionalParamResolved)
    {
        $options = $validConfigObject->getParentConfig();
        $bundlerOptions = array_key_exists('bundler', $options) ? $options['bundler']['drivers'] : [];
        if (count($bundlerOptions) > 0) {
            $defaultDriver = $bundlerOptions['default_driver'];
            $sources = $bundlerOptions['sources'];
            if (count($sources) > 0) {
                if (!in_array($defaultDriver, array_keys($sources))) {
                    throw new BundlerDriverInvalidArgumentException(sprintf(
                        'The driver [ %s ] is invalid. As it was not recognised as 1 of the %s default driver. Your default drivers are [ %s ]',
                        $defaultDriver,
                        count($sources),
                        implode('|', array_keys($sources))
                    ));
                }
                /* Get the selected driver from the sources array */
                $selectedDriver = new \StdClass;
                foreach ($sources as $key => $driver) {
                    if (str_contains($key, $defaultDriver)) {
                        $selectedDriver = $driver;
                    }
                }
                return new $selectedDriver($validConfigObject, $optionalParamResolved);
            }
        }

    }

    /**
     * @param object $concreteObject
     * @param array
     * @return void
     */
    public function makeObjectGlobal(object $concreteObject, array $options): void
    {
        $bundlerOptions = array_key_exists('bundler', $options) ? $options['bundler']['options'] : [];
        if (count($bundlerOptions) > 0) {
            array_key_exists('global_key', $bundlerOptions) &&
            array_key_exists('use_globals', $bundlerOptions) &&
            $bundlerOptions['use_globals'] === true ? GlobalManager::set($bundlerOptions['global_key'], $concreteObject) : false;

        }

    }

    /**
     * @param mixed $optionalParameter
     * @return string|int|bool|array
     */
    public function resolveOptionalParameter(mixed $optionalParameter): string|int|bool|array
    {
        if (is_int($optionalParameter))
            return (int)$optionalParameter;
        if (is_string($optionalParameter))
            return (string)$optionalParameter;
        if (is_array($optionalParameter))
            return $this->resolveArrayParameters($optionalParameter);

        return false;
    }

    /**
     * @param array $optionalParameters
     * @return array|bool
     */
    public function resolveArrayParameters(array $optionalParameters): array|bool
    {
        if (count($optionalParameters) > 0) {
            $params = [];
            foreach ($optionalParameters as $key => $value) {
                if (str_contains($value,'\\')) {
                    $params[$key] = BaseApplication::diGet($value);
                } else {
                    $params[$key] = $value;
                }
            }
            return $params;
        }
        return false;
    }

}