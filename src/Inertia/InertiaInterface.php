<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace MagmaCore\Inertia;

use MagmaCore\Http\ResponseHandler;

interface InertiaInterface
{

/**
     * Adds global component properties for the templating system.
     *
     * @param mixed $value
     */
    public function share(string $key, $value = null): void;

    /**
     * @return mixed
     */
    public function getShared(string $key = null);

    /**
     * Adds global view data for the templating system.
     *
     * @param mixed $value
     */
    public function viewData(string $key, $value = null): void;

    /**
     * @return mixed
     */
    public function getViewData(string $key = null);

    /**
     * Undocumented function
     *
     * @param string $version
     * @return void
     */
    public function version(string $version): void;

    /**
     * Adds a context for the serializer.
     *
     * @param mixed $value
     */
    public function context(string $key, $value = null): void;

    /**
     * @return mixed
     */
    public function getContext(string $key = null);

    /**
     * @return string
     */
    public function getVersion(): ?string;

    /**
     * Undocumented function
     *
     * @param string $rootView
     * @return void
     */
    public function setRootView(string $rootView): void;

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getRootView(): string;

    /**
     * @param string $component component name
     * @param array  $props     component properties
     * @param array  $viewData  templating view data
     * @param array  $context   serialization context
     */
    public function render($component, $props = [], $viewData = [], $context = []): ResponseHandler;

}