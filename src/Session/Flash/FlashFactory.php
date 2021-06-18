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

namespace MagmaCore\Session\Flash;

use MagmaCore\Session\Exception\SessionUnexpectedValueException;
use MagmaCore\Session\SessionInterface;

class FlashFactory
{

    /** @return void */
    public function __construct()
    { }

    /**
     * Session factory which create the session object and instantiate the chosen
     * session storage which defaults to nativeSessionStorage. This storage object accepts
     * the session environment object as the only argument.
     *
     * @param SessionInterface|null $session
     * @param string|null $flashKey
     * @return FlashInterface
     */
    public function create(?SessionInterface $session = null, ?string $flashKey = null) : FlashInterface
    {
        if (!$session instanceof SessionInterface) {
            throw new SessionUnexpectedValueException('Object does not implement session interface.');
        }
        return new Flash($session, $flashKey);
    }

}