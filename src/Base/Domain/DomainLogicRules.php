<?php

declare(strict_types=1);

namespace MagmaCore\Base\Domain;

//use App\Entity\UserEntity;
use MagmaCore\UserManager\UserEntity;
use MagmaCore\Error\Error;

if (!class_exists(UserEntity::class)) {
    die('UserEntity class does not exists within the application');
}

class DomainLogicRules
{

    /**
     * Ensure the password is verified before the action is carried out
     *
     * @param string $value
     * @param string $key
     * @param Object $controller
     * @return void
     */
    public function passwordRequired(string $value, string $key, Object $controller): void
    {
        if (!$controller->repository->verifyPassword($controller, $controller->findOr404()->id)) {
            if ($controller->error) {
                $controller->error->addError(Error::display('err_invalid_credentials'), $controller)->dispatchError();
            }
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

        if (!$controller->repository->isPasswordMatching($controller, new UserEntity($controller->formBuilder->getData()))) {
            if ($controller->error) {
                $controller->error->addError(Error::display('err_mismatched_password'), $controller)->dispatchError();
            }
        }
    }
}
