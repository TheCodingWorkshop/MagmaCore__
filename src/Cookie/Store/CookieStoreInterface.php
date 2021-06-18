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

namespace MagmaCore\Cookie\Store;

interface CookieStoreInterface
{

    /**
     * @return bool
     */
    public function hasCookie(): bool;

    /**
     * @param mixed $value
     * @return void
     */
    public function setCookie(mixed $value): void;

    /**
     * @param null|string $cookieName
     * @return void
     */
    public function deleteCookie(string|null $cookieName = null): void;
}
