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

namespace MagmaCore\Base;

use MagmaCore\Session\Flash\Flash;
use MagmaCore\Base\BaseRedirect;
use MagmaCore\Auth\Authorized;

class BaseAuthentication
{ 

    /** @var BaseRedirect */
    protected BaseRedirect $redirect;
    /** @var Flash */
    protected Flash $flash;

    /**
     * Undocumented function
     *
     * @param BaseRedirect $redirect
     */
    public function __construct(BaseRedirect $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * Provides unauthorized access to certain specified controllers throughout the 
     * framework
     *
     * @return void
     */
    public function loginRequired(string $url)
    {
        $user = Authorized::grantedUser();
        if (!$user) {
            Authorized::rememberRequestedPage();
            $this->redirect->redirect('/login');
        }
    }

}
