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

namespace MagmaCore\Cookie;

interface CookieInterface
{

    /**
     * Set a cookie within the domain
     *
     * @param mixed $value
     * @return void
     */
    public function set(mixed $value) : void;

    /**
     * Checks to see whether the cookie was set or not return true or false
     * 
     * @return bool
     */
    public function has() : bool;

    /**
     * delete a single cookie from the domain
     * 
     * @return void
     */
    public function delete() : void;

    /**
     * Invalid all cookie i.e delete all set cookie within this domain
     * 
     * @return void
     */
    public function invalidate() : void;

}