<?php

namespace MagmaCore\Blank;

use JetBrains\PhpStorm\Pure;
use MagmaCore\Contracts\ConfigurationInterface;
use MagmaCore\Blank\Drivers\BlankDriverInterface;

class Blank extends AbstractBlank
{

    /**
     * @param BlankDriverInterface $blankDriver
     * @param mixed|null $optionalParameters
     */
    #[Pure] public function __construct(BlankDriverInterface $blankDriver, mixed $optionalParameters = null)
    {
        parent::__construct($blankDriver, $optionalParameters);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, mixed $value): void
    {
        $this->getDriver()->setBlank($key, $value);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        if (isset($_SESSION[$key])) {
            return $this->getDriver()->getBlank($key);
        }
    }



}