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

interface InertiaInterface
{

    public function shared(string $key, mixed $value = null): void;
    public function getShared(string $key = null);
    public function viewData(string $key, mixed $value = null): void;
    public function getViewData(string $key = null);
    public function version(string $version): void;
    public function getVersion(): string;
    public function context(string $key, mixed $value = null): void;
    public function getContext(string $key = null);
    public function setRootView(string $rootView): void;
    public function getRootView(): string;
    public function render(string $component, array $props = [], array $viewData = [], array $context = []);

}