<?php

namespace MagmaCore\Blank\Drivers;

use JetBrains\PhpStorm\Pure;
use MagmaCore\Contracts\ConfigurationInterface;

class NativeBlankDriver extends AbstractBlankDriver
{

    #[Pure] public function __construct(ConfigurationInterface $overridingConfig)
    {
        parent::__construct($overridingConfig);
    }

    public function setBlank(string $key, mixed $value): void
    {
        if (!isset($_SESSION[$key]))
            $_SESSION[$key] = $value;
    }

    public function getBlank(string $key): mixed
    {
        return $_SESSION[$key];
    }

}