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

namespace MagmaCore\Ash\Uikit\Components;

use MagmaCore\Ash\Uikit\AbstractUikitComponent;

class Card extends AbstractUikitComponent
{

    public array $props = [
        'type' => 'default',
        'use_header' => false,
        'use_footer' => false,
        'grid' => '',
        'width' => '',
    ];

    /**
     * @param array $properties
     */
    public function __construct(array $properties)
    {
        $this->props = array_merge($this->props, $properties);
    }

    public function render()
    {
        return sprintf(
            '<div class="uk-card uk-card-%s">%s</div>',
            $this->props['type'],
            $this->callback
        );
    }

}

