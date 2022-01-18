<?php

namespace MagmaCore\Blank\Drivers;

interface BlankDriverInterface
{

    public function setBlank(string $key, mixed $value): void;
    public function getBlank(string $key): mixed;


}