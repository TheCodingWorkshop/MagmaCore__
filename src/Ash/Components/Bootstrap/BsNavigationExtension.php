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

namespace MagmaCore\Ash\Components\Bootstrap;

use MagmaCore\Ash\TemplateExtensionBuilderInterface;

class BsNavigationExtension
{

    public const NAME = 'bs_navigation';
    private string $element = '';
    protected $active;
    protected $status;
    protected array $roles = [];
    protected string $elementClass = 'navbar-nav bg-gradient-primary sidebar sidebar-dark accordion';

    public function register(array $items, array $routes = [], bool $wrapper = false): string
    {
        $this->status = ' hide';
        if (count($items) > 0) {
            ($wrapper === true) ? '<ul class="' . $this->elementClass . '">' . PHP_EOL : false;
            foreach ($items as $key => $value) {
                $this->active = '';

                if (in_array($value['name'], $routes)) {
                    $this->active = ' active';
                    $this->status = ' show';
                }

                $this->element .= '<li class="nav-item' . (isset($this->active) ? $this->active : '') . '">';
                $children = (isset($value['children']) ? $value['children'] : []);
                $this->element .= '<a href="' . (isset($value['path']) ? $value['path'] : '') . '" class="nav-link active' . (count($children) > 0 ? ' collapsed' : '') . '"' . ((count($children) > 0) ? ' data-toggle="collapse" data-target="#collapse' . (isset($value['name']) ? $value['name'] : '') . '" aria-expanded="true" aria-controls="collapse' . (isset($value['name']) ? $value['name'] : '') . '"' : '') . '>' . PHP_EOL;


                $this->element .= '<i class="' . (isset($value['icon']) ? $value['icon'] : 'question') . '" style="font-size: 1.2rem;"></i>';

                $this->element .= ' <span>' . (isset($value['name']) ? $value['name'] : 'Unknown') . '</span>';
                $this->element .= '</a>' . PHP_EOL;
                /* render child items in parent items contains any */
                $this->element .= $this->childrenBlock($children, $value);
                $this->element .= '</li>' . PHP_EOL;
                /* render the item separator block */
                $this->element .= $this->subBlock($value);
            }
            ($wrapper === true) ? '</ul>' . PHP_EOL : false;
        }

        return $this->element;
    }

    /**
     * Render children menu items
     *
     * @param array $children
     * @param array $value
     * @return string
     */
    private function childrenBlock(array $children = [], array $value = []): string
    {
        $element = '';
        if (count($children) > 0) {
            $element .= ' <div id="collapse' . $value['name'] . '" class="collapse' . (isset($this->status) ? $this->status : '') . '" aria-labelledby="heading' . ucwords($value['name']) . '" data-parent="#accordionSidebar">' . PHP_EOL;
            $element .= '<div class="bg-white py-2 collapse-inner rounded">';
            $element .= '<h6 class="collapse-header">' . (isset($value['title']) ? $value['title'] : '') . '</h6>';
            foreach ($children as $child) {
                $element .= '<a class="collapse-item" href="' . (isset($child['path']) ? $child['path'] : '') . '">' . (isset($child['name']) ? $child['name'] : '') . '</a>';
            }
            $element .= '</div>' . PHP_EOL;
            $element .= '</div>' . PHP_EOL;
        }
        return $element;
    }

    /**
     * Render item separators
     *
     * @param array $value
     * @return string
     */
    private function subBlock(array $value): string
    {
        $element = '';
        if (isset($value['header']) && $value['header'] !== '') {
            $element .= '<hr class="sidebar-divider">';
            $element .= '<div class="sidebar-heading">';
            $element .= (isset($value['header']) ? $value['header'] : '');
            $element .= '</div>' . PHP_EOL;
        }
        return $element;
    }

    /**
     * Return true of the currently selected menu items present the controller
     * route controller
     *
     * @param array $value
     * @param array $routes
     * @return boolean
     */
    private function isActive(array $value, array $routes): bool
    {
        if (in_array($value['name'], $routes)) {
            return true;
        }
        return false;
    }
}
