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

namespace MagmaCore\Session\Storage;

class PdoSessionStorage extends AbstractSessionStorage
{

    /**
     * Main class constructor
     *
     * @param Object $sessionEnvironment
     */
    public function __construct(Object $sessionEnvironment)
    {
        parent::__construct($sessionEnvironment);
    }

}