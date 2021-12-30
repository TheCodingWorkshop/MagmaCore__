<?php

namespace MagmaCore\Blank\Drivers;

use JetBrains\PhpStorm\Pure;
use MagmaCore\Contracts\ConfigurationInterface;

class PdoBlankDriver extends AbstractBlankDriver
{

    #[Pure] public function __construct(ConfigurationInterface $overridingConfig)
    {
        parent::__construct($overridingConfig);
    }

    public function setBlank(string $key, mixed $value): void
    {
    }

    public function getBlank(string $key): mixed
    {
        return $key;
    }

}
