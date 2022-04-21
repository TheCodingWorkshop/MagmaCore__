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

namespace MagmaCore;

class IconLibrary
{

    /**
     * Return the fully formatted icon for the specified type
     *
     * @param string $iconName
     * @param integer|float|string|null $iconSize
     * @param string|null $type
     * @return string
     */
    public static function getIcon(string $iconName, int|float|string $iconSize = null, ?string $type = 'uikit'): string
    {
        return match($type) {
            'uikit' => sprintf('<span uk-icon="%s%s"></span>', (isset($iconName) ? 'icon: ' . $iconName: ''), (isset($iconSize) ? ' ;ratio: ' . $iconSize : '')),
            'ion' => sprintf('<ion-icon name="%s" class="%s"></ion-icon>', (isset($iconName) ? $iconName : ''), (isset($iconSize) ? 'ion-' . $iconSize : 'ion-18')),
            'fontawesome' => '',
            'img' => '<span><img src="/public/assets/images/' . (isset($iconName) ? $iconName : '') . '" width="' . (isset($iconSize) ? $iconSize : '') . 'px"></span>',
            default => ''
        };
    }

}