<?php

namespace MagmaCore\Contracts;

interface FactoryInterface
{

    public function create(?string $driver = null, array $overridingConfig = []): object;

}