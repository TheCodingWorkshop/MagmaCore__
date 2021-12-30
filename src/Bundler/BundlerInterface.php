<?php

namespace MagmaCore\Bundler;

interface BundlerInterface
{

    public static function register(): array;
    public static function unregister(): void;

}