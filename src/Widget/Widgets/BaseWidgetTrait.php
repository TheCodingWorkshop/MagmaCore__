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

namespace MagmaCore\Widget\Widgets;

trait BaseWidgetTrait
{

    public static function resolvedLists(array $array = [], ?string $item = null): string
    {
        foreach ($array as $key => $value) {
            return sprintf(
                '
                <li>
                    <div class="uk-grid-small" uk-grid>
                        <div class="uk-width-expand" uk-leader>%s</div>
                        <div>%s%</div>
                    </div>
                </li>
                ',
                $key,
                $value[$item]
            );
        }

    }

}