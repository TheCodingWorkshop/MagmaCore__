<?php

declare(strict_types=1);

namespace MagmaCore\Inertia;

//use App\Entity\Account;
//use App\Entity\User;
use MagmaCore\Inertia\AbstractInertiaController;

class BaseInertiaController extends AbstractInertiaController
{
    protected function getCurrentUserAccount(): Account
    {
        /** @var ?User $currentUser */
        //$currentUser = $this->getUser();

       /* if ($currentUser === null) {
            throw new \RuntimeException('There is no currently logged in user.');
        }

        $account = $currentUser->getAccount();

        if ($account === null) {
            throw new \RuntimeException("The current user's account must not be null.");
        }

        return $account;*/
    }
}