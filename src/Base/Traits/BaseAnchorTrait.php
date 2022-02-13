<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MagmaCore\Base\Traits;

use MagmaCore\IconLibrary;
use MagmaCore\Auth\Roles\PrivilegedUser;

trait BaseAnchorTrait
{

    public function protectedAnchor(
        array $props = [], ?int $userID = null, mixed $content = null, array $permissions = [], ?callable $callback = null): string
    {
        $privilege = PrivilegedUser::getUser();
        if (count($permissions) > 0) {
            foreach ($permissions as $permission) {
                if ($privilege->hasPrivilege($permission)) {
                    if (count($props) > 0) {
                        if (isset($content) && $content !==null) {
                            return $this->anchorTag($props, $callback, $content);
                        }
                    }

                }
            }
        }
        /* return a dummy link if the current logged in user doesn't have the correct permission */
        return sprintf(
            '<a href="%s" uk-tooltip="%s" class="uk-link-reset">%s %s</a>', 
            'javascript:void()', 
            'You don\'t haver permission to access this route',
            IconLibrary::getIcon('ban', 0.6),
            $content
        );
        //return '<a class="uk-disabled uk-reset-link" href="javascript:void()">' . $content . '</a>';
    }

    /**
     * @param array $props
     * @param callable|null $callback
     * @param mixed $content
     * @return string
     */
    public function anchorTag(array $props, ?callable $callback, mixed $content): string
    {
        return sprintf('%s <a %s%s%s%s%s%s%s>%s</a>',
            array_key_exists('icon', $props) ? IconLibrary::getIcon($props['icon'], 0.7) : '',

            array_key_exists('href', $props) ? ' href="' . $props['href'] . '"' : '',
            array_key_exists('class', $props) ? ' class="' . $props['class'] . '"' : '',
            array_key_exists('id', $props) ? ' id="' . $props['id'] . '"' : '',
            array_key_exists('title', $props) ? ' title="' . $props['title'] . '"' : '',
            array_key_exists('turbo', $props) ? ' data-turbo="' . $props['turbo'] . '"' : '',
            array_key_exists('class', $props) ? ' id="' . $props['id'] . '"' : '',
            isset($callback) && $callback !== null ? $callback : null,
            $content
        );
    }


}