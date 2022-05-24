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

namespace MagmaCore\UserManager\Security;

use MagmaCore\UserManager\Security\Event\LogoutActionEvent;
use MagmaCore\UserManager\Security\Form\LogoutForm;
use MagmaCore\Auth\Authenticator;
use MagmaCore\Base\BaseController;
use MagmaCore\Base\Domain\Actions\LogoutAction;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

class LogoutController extends BaseController
{

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
         * [ userModel => \App\Model\UserModel::class ]. Where the key becomes the
         * property for the userModel object like so $this->userModel->getRepo();
         */
        $this->diContainer(
            [
                'logoutForm' => LogoutForm::class,
                'logoutAction' => LogoutAction::class,
                'authenticator' => Authenticator::class,
            ]
        );
    }

    /**
     * Middleware which are executed before any action methods is called
     * middlewares are return within an array as either key/value pair. Note
     * array keys should represent the name of the actual class its loading ie
     * upper camel case for array keys. alternatively array can be defined as
     * an index array omitting the key entirely
     *
     * @return array
     */
    protected function callAfterMiddlewares(): array
    {
        return [
            //'LogoutIfNoSession' => LogoutIfNoSession::class,
        ];
    }

    /**
     * Authorized logging out the current user. This will destroy the entire user
     * session and clear the remembered_logins database table of any cookies
     *
     * @return void
     */
    protected function logoutAction()
    {
        $this->logoutAction
            ->execute($this, NULL, LogoutActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with()
            ->form($this->logoutForm)
            ->end();
    }

}
