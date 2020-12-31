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

use MagmaCore\Session\Flash\FlashInterface;
use MagmaCore\Session\Flash\FlashType;
use MagmaCore\Session\SessionTrait;
use MagmaCore\Session\SessionInterface;

class Flash implements FlashInterface
{

    /** Contains the session object */
    use SessionTrait;

    /** @var string */
    protected const FLASH_KEY = 'flash_message';
    /** @var string */
    protected string $flashKey;
    /** @var SessionInterface */
    protected SessionInterface $session;

    /**
     * Class constructor method which accepts a single default argument
     * which allows the user to specfies their own flash key as a option
     * else if not present will use the default set by the framework
     * 
     * @param null|string $flashKey
     * @return void
     */
    public function __construct(?Object $session = null, ?string $flashKey = null)
    {
        $this->session = $session;
        if ($flashKey !=null) {
            $this->flashKey = $flashKey;
        } else {
            $this->flashKey = self::FLASH_KEY;
        }
    }

    /**
     * @inheritdoc
     * 
     * @param string $message
     * @param string $type
     * @return void
     */
    public function add(string $message, ?string $type = null) : void
    {
        /* Apply default constants to flash type */
        if ($type === null) {
            $type = FlashType::SUCCESS;
        }
        if ($this->session->has($this->flashKey)) {
            $this->session->set($this->flashKey, []);
        }
        $this->session->setArray($this->flashKey,
            [
                'message' => $message,
                'type' => $type
            ]
        );
    }

    /**
     * @inheritdoc
     * 
     * @return mixed
     */
    public function get()
    {
        if ($this->session->has($this->flashKey)) {
            $this->session->flush($this->flashKey);
        }
    }

}