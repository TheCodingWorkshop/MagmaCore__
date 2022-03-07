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

use MagmaCore\Widget\Widgets\BaseWidget;
use MagmaCore\Widget\Exception\WidgetException;
use MagmaCore\Widget\Widgets\Notifications\NotificationWidget;
use MagmaCore\Widget\Widgets\Tickets\TicketWidget;
use MagmaCore\Widget\Widgets\Cards\ActiveNowWidget;
use MagmaCore\Widget\Widgets\Cards\BounceRateWidget;
use MagmaCore\Widget\Widgets\Cards\TotalVisitWidget;
use MagmaCore\Widget\Widgets\ListsWidget\ListsWidget;
use MagmaCore\Widget\Widgets\BackupStatus\BackupStatusWidget;
use MagmaCore\Widget\Widgets\SystemLogger\SystemLoggerWidget;
use MagmaCore\Widget\Widgets\WebsiteTraffic\WebsiteTrafficWidget;
use MagmaCore\DataObjectLayer\ClientRepository\ClientRepositoryInterface;
use MagmaCore\Widget\Widgets\Members\MemberWidget;

/**
 * All widgets must be registered within this Widget class before it becomes available for use
 * within the template
 */
class Widget extends AbstractWidget
{

    /* @var array widgets */
    private const ALLOWED_WIDGETS = [
        ActiveNowWidget::WIDGET_NAME => ['class' => ActiveNowWidget::class],
        BounceRateWidget::WIDGET_NAME => ['class' => BounceRateWidget::class],
        TotalVisitWidget::WIDGET_NAME => ['class' => TotalVisitWidget::class],
        BackupStatusWidget::WIDGET_NAME => ['class' => BackupStatusWidget::class],
        SystemLoggerWidget::WIDGET_NAME => ['class' => SystemLoggerWidget::class],
        WebsiteTrafficWidget::WIDGET_NAME => ['class' => WebsiteTrafficWidget::class],
        ListsWidget::WIDGET_NAME => ['class' => ListsWidget::class],
        TicketWidget::WIDGET_NAME => ['class' => TicketWidget::class],
        MemberWidget::WIDGET_NAME => ['class' => MemberWidget::class],
        NotificationWidget::WIDGET_NAME => ['class' => NotificationWidget::class],

    ];

    /* @var $clientRepo */
    protected ClientRepositoryInterface $clientRepo;
    /* @var array */
    protected array $widgetPackage = [];
    /* @var string */
    private ?string $widgetName = null;

    /**
     * Main Widget class constructor
     *
     * @param ?ClientRepositoryInterface $clientRepo
     * @param array $widgetPackage
     */
    public function __construct(?ClientRepositoryInterface $clientRepo = null, array $widgetPackage = [])
    {
        $this->clientRepo = $clientRepo;
        $this->baseWidget = new BaseWidget();
        /* the widget package contain the database parameters the widget may wants access to */
        list($this->widgetName, $this->widgetSchema, $this->widgetSchemaID) = $widgetPackage;
    }

    /**
     * @inheritDoc
     */
    public function renderWidget(mixed $widgetData = null): mixed
    {
        if (!in_array($this->widgetName, array_keys(self::ALLOWED_WIDGETS))) {
            throw new WidgetException(sprintf('Invalid widget %s. Does not exists within the widget library', $this->widgetName));
        }
        if (is_array(self::ALLOWED_WIDGETS) && count(self::ALLOWED_WIDGETS) > 0) {
            foreach (self::ALLOWED_WIDGETS as $widgetName => $widgetClass) {
                if (str_contains($widgetName, $this->widgetName)) {
                    switch ($widgetName) {
                        case $widgetName :
                            return $widgetClass['class']::render(
                                $this->widgetName,
                                $this->clientRepo,
                                $this->baseWidget,
                                $widgetData
                            );
                            break;
                    }
    
                }
            }
        }

        return false;
    }
}
