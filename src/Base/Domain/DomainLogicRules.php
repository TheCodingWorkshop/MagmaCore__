<?php

declare(strict_types=1);

namespace MagmaCore\Base\Domain;

use App\Entity\UserEntity;

class DomainLogicRules
{

    private const CONTROLLER_CLASS = [];

    /**
     * Undocumented function
     *
     * @return void
     */
    public function passwordRequired(string $value, string $key, Object $controller): void
    {
        if (!$controller->repository->verifyPassword($controller, $controller->findOr404()->id)) {
            if ($controller->error) {
                $controller->error->addError(['incorrect_password' => 'Incorrect Password for this account.!'], $controller)->dispatchError();
            }
        }
    }

    public function passwordEqual(string $value, string $key, Object $controller)
    {
        $this->passwordRequired($value, $key, $controller);

        if (!$controller->repository->isPasswordMatching($controller, new UserEntity($controller->formBuilder->getData()))) {
            if ($controller->error) {
                $controller->error->addError(['mismatched_password' => 'Your password does not match.'], $controller)->dispatchError();
            }
        }
    }
}
