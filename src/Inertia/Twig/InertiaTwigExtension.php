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

namespace MagmaCore\Inertia\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;

class InertiaTwigExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [new TwigFunction('inertia', [$this, 'inertiaFunction'])];
    }

    public function inertiaFunction($page)
    {
        return new Markup('<div id="app" data-page="' . htmlspecialchars(json_encode($page)) . '"></div>', 'UTF-8');
    }

}