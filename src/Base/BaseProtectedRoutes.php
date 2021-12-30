<?php

declare(strict_types=1);

namespace MagmaCore\Base;

use MagmaCore\Auth\Roles\PrivilegedUser;

class BaseProtectedRoutes
{

    public function __invoke()
    {
        $privilege = PrivilegedUser::getUser();
        if (!$privilege->hasPrivilege($permission . '_' . $controller->thisRouteController())) {
            $controller->flashMessage('Access Denied!', $controller->flashWarning());
            $controller->redirect('/admin/accessDenied/index');
        }
        return $this;

    }


}