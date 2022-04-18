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

namespace MagmaCore\History;

use MagmaCore\Administrator\Controller\AdminController;
use MagmaCore\Base\Domain\Actions\HistoryAction;
use MagmaCore\History\Event\HistoryActionEvent;

class HistoryController extends AdminController
{

    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        $this->addDefinitions(
            [
                'historyAction' => HistoryAction::class,
                'repository' => HistoryModel::class,
                'entity' => HistoryEntity::class,
                'column' => HistoryColumn::class,
                'commander' => HistoryCommander::class,
                'historyForm' => HistoryForm::class,
            ]
        );
    }

    protected function indexAction()
    {
        $this->historyAction
            ->setAccess($this, 'can_view')
            ->execute($this, NULL, HistoryActionEvent::class, HistorySchema::class, __METHOD__)
            ->render()
            ->with()
            ->list()
            ->end();
    }


}