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

namespace MagmaCore\PanelMenu;

use MagmaCore\PanelMenu\MenuCommander;
use MagmaCore\PanelMenu\MenuColumn;
use MagmaCore\PanelMenu\MenuSchema;
use MagmaCore\PanelMenu\MenuForm;
use MagmaCore\PanelMenu\Event\MenuActionEvent;
use MagmaCore\Auth\Authorized;
use MagmaCore\Auth\Entity\MenuEntity;
use MagmaCore\Auth\Model\MenuModel;
use MagmaCore\Auth\Model\MenuItemModel;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\DataObjectLayer\DataLayerTrait;

class MenuController extends \MagmaCore\Administrator\Controller\AdminController
{

    use DataLayerTrait;

    /**
     * Extends the base constructor method. Which gives us access to all the base
     * methods implemented within the base controller class.
     * Class dependency can be loaded within the constructor by calling the
     * container method and passing in an associative array of dependency to use within
     * the class
     *
     * @param array $routeParams
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        /**
         * Dependencies are defined within a associative array like example below
         * [ roleModel => \App\Model\RoleModel::class ]. Where the key becomes the
         * property for the RoleModel object like so $this->roleModel->getRepo();
         */
        $this->addDefinitions(
            [
                'repository' => MenuModel::class,
                'commander' => MenuCommander::class,
                'column' => MenuColumn::class,
                'entity' => MenuEntity::class,
                'formMenu' => MenuForm::class,
                'menuItem' => MenuItemModel::class
            ]
        );
    }

    /**
     * Returns a 404 error page if the data is not present within the database
     * else return the requested object
     *
     * @return mixed
     */
    public function findOr404(): mixed
    {
        return $this->repository->getRepo()
            ->findAndReturn($this->thisRouteID())
            ->or404();
    }

    protected function indexAction()
    {
        $this->indexAction
            ->setAccess($this, 'can_view')
            ->execute($this, NULL, NULL, MenuSchema::class, __METHOD__)
            ->render()
            ->with()
            ->table()
            ->end();
    }

    protected function newAction()
    {
        $this->indexAction
            ->setAccess($this, 'can_add')
            ->execute($this, NULL, NULL, MenuSchema::class, __METHOD__)
            ->render()
            ->with(
                [

                ]
            )
            ->form($this->formMenu)
            ->end();
    }

    protected function editAction()
    {
        $this->editAction
            ->setAccess($this, 'can_edit')
            ->execute($this, MenuEntity::class, MenuActionEvent::class, NULL, __METHOD__, [],
                ['item_usable' => (isset($_POST['item_usable']) && count($_POST['item_usable']) > 0 ? $_POST['item_usable'] : [])]
            )
            ->render()
            ->with(
                [
                    'parent_menu' => $this->toArray($this->findOr404()),
                    'menu_items' => $this->menuItem->getRepo()->findBy(['*'], ['item_original_id' => $this->thisRouteID(), 'item_usable' => 1]),
                ]
            )
            ->form($this->formMenu)
            ->end();
    }

    /**
     * Remove a menu item from the usable list of items
     * @return bool
     */
    protected function removeItemAction(): bool
    {
        if (isset($this->formBuilder)) {
            if ($this->formBuilder->canHandleRequest()) {
                $queriedID = $this->thisRouteID() ?? null;
                $remove = $this->menuItem->getRepo()->findByIdAndUpdate(['item_usable' => NULL], $queriedID);
                if ($remove === true) {
                    $originalMenuID = $this->request->handler()->get('menu_id') ?? '/admin/menu/index';
                    $originalMenuID = (int)$originalMenuID;
                    $this->flashMessage('The item was remove from the usable list');
                    $this->redirect('/admin/menu/' . $originalMenuID . '/edit');
                }
            }
        }
        return false;
    }


}

