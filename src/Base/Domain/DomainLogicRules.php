<?php

declare(strict_types=1);

namespace MagmaCore\Base\Domain;

use App\Entity\UserProfileEntity;
use MagmaCore\Error\Error;

if (!class_exists(UserProfileEntity::class)) {
    die('UserEntity class does not exists within the application');
}

class DomainLogicRules
{

    /**
     * Redirect on error
     *
     * @param object $controller
     * @param string $msg
     * @return void
     */
    public function errorRedirect(object $controller, string $msg): void
    {
        $controller->flashMessage($msg, $controller->flashWarning());
        $controller->redirect($controller->onSelf());

    }

    /**
     * Ensure the password is verified before the action is carried out
     *
     * @param string $value
     * @param string $key
     * @param object $controller
     * @return void
     */
    public function passwordRequired(string $value, string $key, Object $controller): void
    {
        if (!$controller->repository->verifyPassword($controller, $controller->findOr404()->id)) {
            $this->errorRedirect(
                $controller, 
                array_values(Error::display('err_invalid_credentials'))[0]
            );
        }
    }

    /**
     * Double checks the user password before persisting to database
     *
     * @param string $value
     * @param string $key
     * @param Object $controller
     * @return void
     */
    public function passwordEqual(string $value, string $key, Object $controller)
    {
        $this->passwordRequired($value, $key, $controller);

        if (!$controller->repository->isPasswordMatching($controller, new UserProfileEntity($controller->formBuilder->getData()))) {
            $this->errorRedirect(
                $controller, 
                array_values(Error::display('err_mismatched_password'))[0]
            );

        }
    }
}
