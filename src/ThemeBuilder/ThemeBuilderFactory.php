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

namespace MagmaCore\ThemeBuilder;

use MagmaCore\Base\BaseApplication;
use MagmaCore\ThemeBuilder\Exception\ThemeBuilderInvalidArgumentException;

class ThemeBuilderFactory
{

    /**
     * @param string|null $cssDriver
     * @param array $cssOptions
     * @return ThemeBuilderInterface
     */
    public function create(?string $cssDriver = null, array $cssOptions = []): ThemeBuilderInterface
    {
        $cssDriverObject = new $cssDriver($cssOptions);
        if (!$cssDriverObject) {
            throw new ThemeBuilderException(sprintf('%s is not a valid object.', $cssDriver));
        }
        return new ThemeBuilder($cssDriverObject);

    }


}