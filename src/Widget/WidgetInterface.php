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

namespace MagmaCore\Widget;

interface WidgetInterface
{

    /**
     * Render the final widget to the client browser
     *
     * @param mixed $widgetData - Data which can be pass back to the widget component
     * @return mixed
     */
    public function renderWidget(mixed $widgetData = null): mixed;

}