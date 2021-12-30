<?php

namespace MagmaCore\Blank;

use MagmaCore\Blank\Exception\BlankDriverMissigDriverException;
use MagmaCore\Blank\Drivers\BlankDriverInterface;
use MagmaCore\Blank\Exception\BlankDriverMissingDriverException;

trait BlankTrait
{

    /**
     * @param string|null $driver
     * @param object $validConfigObject
     * @param mixed|null $optionalParamResolved
     * @return BlankDriverInterface
     */
    private function theDriver(?string $driver, object $validConfigObject, mixed $optionalParamResolved = null): BlankDriverInterface
    {
        $newDriver = ($driver !==null) ? new $driver($validConfigObject, $optionalParamResolved) : $this->resolveDefaultDriver($validConfigObject, $optionalParamResolved);
        if (!$newDriver instanceof BlankDriverInterface) {
            throw new BlankDriverMissingDriverException(sprintf(
                '[ % ] does not implement the BlankDriverInterface. Please ensure your concrete class implements this interface.',
                $driver
            ));
        }
        return $newDriver;

    }


}