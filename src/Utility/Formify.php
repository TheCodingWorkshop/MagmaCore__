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

namespace MagmaCore\Utility;

class Formify
{

    public function referrer(): string
    {
        return '<input type="hidden" name="referredby" id="referredby" value="' . $_SERVER['HTTP_REFERER'] . '">';
    }

    /**
     * Undocumented function
     *
     * @param [type] $checked
     * @param [type] $current
     * @return string
     */
    public function isChecked($checked, $current): string
    {   
        if ($checked == $current)
            return ' checked="checked"';
    }

    /**
     * Undocumented function
     *
     * @param $selected
     * @param $current
     * @return bool|string
     */
    public function isSelected($selected, $current): bool|string
    {
        if ($selected == $current)
            return ' selected="selected"';
    }


}
