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

namespace MagmaCore\Ash\Extensions\Modules;

class SearchBoxExtension
{

    public function triggerSearchBox()
    {
        return '
            <a uk-tooltip="Search" uk-icon="icon:search; ratio:1.5" class="uk-navbar-toggle uk-text-muted" uk-toggle="target: .nav-overlay; animation: uk-animation-fade" href="#"></a>
        ';
    }

    public function searchBox(string $filter = 's', string $placeholder = 'Search...'): string
    {
        return '
        <div class="nav-overlay uk-navbar-left uk-flex-1" hidden>
            <div class="uk-navbar-item uk-width-expand">
                <form class="uk-search uk-search-navbar uk-width-1-1">
                    <input class="uk-search-input" name="' . $filter . '" type="search" placeholder="' . $placeholder . '" autofocus>
                </form>
            </div>
            <a uk-tooltip="Close" uk-icon="icon:close; ratio:1.5" class="uk-navbar-toggle uk-text-muted" uk-toggle="target: .nav-overlay; animation: uk-animation-fade" href="#"></a>
        </div>

        ';
    }
}
