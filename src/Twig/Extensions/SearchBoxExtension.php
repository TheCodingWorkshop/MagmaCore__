<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare (strict_types = 1);

namespace MagmaCore\Twig\Extensions;

class SearchBoxExtension
{

    public function searchBox(string $filter = 's', string $placeholder = 'Search...') : string
    {
        return '
        <div class="uk-margin uk-margin-remove-bottom">
        <form class="uk-search uk-search-default" id="searchForm">
            <a href="" uk-search-icon></a>
            <input class="uk-search-input" type="search" name="' . $filter . '" placeholder="' . $placeholder . '">
        </form>
    </div>

        ';

    }

}
