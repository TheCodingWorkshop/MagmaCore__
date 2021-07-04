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

namespace MagmaCore\Themes;

use MagmaCore\Themes\Exception\ThemeBuilderInvalidArgumentException;

class ThemeBuilder
{

    protected object $themeBuilder;

    public function create(string $themeBuilder): object|string
    {
        $this->themeBuilder = new $themeBuilder();
        if (!$this->themeBuilder instanceof ThemeBuilderInterface) {
            throw new ThemeBuilderInvalidArgumentException('Invalid theme builder object. Ensure you are implementing the correct interface [ThemeBuilderInterface]');
        }
        return $this->themeBuilder;
    }

}