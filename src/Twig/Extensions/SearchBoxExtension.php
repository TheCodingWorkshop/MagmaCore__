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

namespace MagmaCore\Twig\Extensions;

class SearchBoxExtension
{

    public function searchBox(string $filter = 's', string $placeholder = 'Search...'): string
    {
        return '
        <div class="uk-navbar-item">
        <form class="uk-search uk-search-navbar">
            <span uk-search-icon></span>
            <input class="uk-search-input" name="' . $filter . '" type="search" placeholder="' . $placeholder . '">
        </form>
    </div>

        ';
    }
}
