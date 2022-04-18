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

use Closure;
use MagmaCore\Widget\Exception\WidgetException;

class BaseWidget
{

    use BaseWidgetTrait;

    /**
     * Base card widget wrapper for all card based widgets within this cards directory
     *
     * @param Closure|null $callback
     * @param string|null $cardColor
     * @return string
     */
    public static function card(Closure $callback = null, ?string $cardColor = 'secondary'): string
    {
        if (!$callback instanceof Closure) {
            throw new WidgetException(sprintf('%s is not a Closure', $callback));
        }
        $html = '<div class="uk-card uk-card-' . $cardColor . ' uk-card-body">';
        $html .= $callback(new self);
        $html .= '</div>';

        return $html;
    }

    /**
     * off-canvas slide out panel
     *
     * @param Closure|null $callback
     * @param string|null $trigger
     * @return string
     */
    public static function offCanvas(Closure $callback = null, ?string $trigger): string
    {
        if (!$callback instanceof Closure) {
            throw new WidgetException(sprintf('%s is not a Closure', $callback));
        }
        $html = '<div id="' . $trigger . '" uk-offcanvas="flip: true; overlay: true">';
            $html .= '<div class="uk-offcanvas-bar uk-background-secondary">';
                $html .= '<button class="uk-offcanvas-close" type="button" uk-close></button>';
                $html .= $callback(new self);
            $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

}
