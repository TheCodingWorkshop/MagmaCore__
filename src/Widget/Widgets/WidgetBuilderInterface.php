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

use MagmaCore\Widget\Widgets\BaseWidget;
use MagmaCore\DataObjectLayer\ClientRepository\ClientRepositoryInterface;

interface WidgetBuilderInterface
{

    /**
     * Render the widget
     *
     * @param string|null $widgetName - the name of the rendering widget
     * @param ClientRepositoryInterface $clientRepo - database access to access the specified table
     * @param BaseWidget $baseWidget - contains standard method to aiding in constructing a widget
     * @param mixed $widgetData - template specific data passed in
     * @return string - Should always return a formatted string
     */
    public static function render(?string $widgetName = null, ClientRepositoryInterface $clientRepo, BaseWidget $baseCard, mixed $widgetData = null): string;

}
