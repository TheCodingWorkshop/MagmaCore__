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

namespace MagmaCore\Auth\Controller;

use MagmaCore\Base\BaseController;

class AuthenticatedController extends BaseController
{
    /**
     * Filter which gets fired before an action method is called. In this case
     * any controller which extends this class will be required a active login
     * session. Else a user will be redirect log in.
     * @throws Exception|Throwable
     */
    protected function before()
    {
        $this->loginRequired();
    }

}