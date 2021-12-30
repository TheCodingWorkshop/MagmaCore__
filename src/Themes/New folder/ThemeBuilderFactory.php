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

class ThemeBuilderFactory
{

    protected object $themeDefault;

    /**
     * Create the themeBuilder object and pass the theme builder library defaults to UIKIT
     * 
     * @param string $themeDefault
     * @param array $themeOptions
     * @return object
     */
    public function create(string $themeDefault, array $themeOptions = [])
    {
        $_themeDefault = new $themeDefault($themeOptions);
        if (!$_themeDefault) {
            throw new ThemeBuilderInvalidArgumentException('Invalid theme builder object library. Ensure you are implementing the correct interface [ThemeBuilderInterface]');
        }
        return new ThemeBuilder();
        //return (new ThemeBuilder())->build($_themeDefault);
    }

}