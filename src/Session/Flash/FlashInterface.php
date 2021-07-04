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

interface FlashInterface
{

    /**
     * Method for adding a flash message to the session
     *
     * @param string $message
     * @param string|null $type
     * @return void
     */
    public function add(string $message, ?string $type = null) : void;

    /**
     * Get all the flash messages from the session
     * 
     * @return mixed
     */
    public function get(): mixed;

}