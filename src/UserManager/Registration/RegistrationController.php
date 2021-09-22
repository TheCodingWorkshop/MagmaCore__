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

namespace MagmaCore\UserManager\Registration;

use MagmaCore\UserManager\Registration\Event\RegistrationActionEvent;
use MagmaCore\UserManager\Registration\RegistrationForm;
use MagmaCore\UserManager\UserEntity;
use MagmaCore\UserManager\UserModel;
use MagmaCore\Base\BaseController;
use MagmaCore\Base\Domain\Actions\NewAction;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

class RegistrationController extends BaseController
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
        $this->addDefinitions(
            [
                'formRegister' => RegistrationForm::class,
                'repository' => UserModel::class,
                'newAction' => NewAction::class
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
    protected function callBeforeMiddlewares(): array
    {
        return [];
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
        return [];
    }

    /**
     * Entry method which is hit on request. This method should be implement within
     * all sub controller class as a default landing point when a request is
     * made. The properties within the method below is extended from the parent
     * class AuthSecurityController
     */
    protected function registerAction()
    {
        $this->newAction
            ->execute($this, UserEntity::class, RegistrationActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with()
            ->form($this->formRegister)
            ->end();

    }

    /**
     * Rendered the user message after successfully registering their account
     *
     * @return void
     */
    protected function registeredAction()
    {
        $this->render(
            "client/registration/registered.html", []
        );
    }
}
