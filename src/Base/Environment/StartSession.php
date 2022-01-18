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

namespace MagmaCore\Base\Environment;

use MagmaCore\Session\SessionConfig;
use MagmaCore\Base\Exception\BaseLengthException;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

class StartSession
{

    protected array $session = [];
    protected bool $isSessionGlobal = false;
    protected ?string $globalSessionKey = null;

    /**
     * Undocumented function
     *
     * @param array $ymlSession
     * @param string|null $newSessionDriver
     * @param boolean $isGlobal
     * @param string|null $globalKey
     * @return self
     */
    public function setSession(array $ymlSession = [], ?string $newSessionDriver = null, bool $isGlobal = false, ?string $globalKey = null): self
    {
        $this->session = (!empty($ymlSession) ? $ymlSession : (new SessionConfig())->baseConfiguration());
        $this->newSessionDriver = ($newSessionDriver !== null) ? $newSessionDriver : $this->getDefaultSessionDriver();
        $this->isSessionGlobal = $isGlobal;
        $this->globalSessionKey = $globalKey;

        return $this;
        
    }

    /**
     * Return the session yml configuration array
     *
     * @return array
     */
    public function getSession(): array
    {
        if (count($this->session) < 0) {
            throw new BaseInvalidArgumentException('You have no session configuration. This is required.');
        }
        return $this->session;
    }

        /**
     * Returns the default session driver from either the core or user defined
     * session configuration. Throws an exception if neither configuration
     * was found
     *
     * @return string
     * @throws BaseInvalidArgumentException
     */
    public function getSessionDriver(): string
    {
        if (empty($this->session)) {
            throw new BaseInvalidArgumentException('You have no session configuration. This is required.');
        }
        return $this->newSessionDriver;
    }

    /**
     * Turn on global session from public/index.php bootstrap file to make the session
     * object available globally throughout the application using the GlobalManager object
     * @return bool
     */
    public function isSessionGlobal(): bool
    {
        return isset($this->isSessionGlobal) && $this->isSessionGlobal === true ? true : false;
    }

    /**
     * @return string
     * @throws BaseLengthException
     */
    public function getGlobalSessionKey(): string
    {
        if ($this->globalSessionKey !==null && strlen($this->globalSessionKey) < 3) {
            throw new BaseLengthException($this->globalSessionKey . ' is invalid this needs to be more than 3 characters long');
        }
        return ($this->globalSessionKey !==null) ? $this->globalSessionKey : 'session_global';
    }

    /**
     * Get the default session driver defined with the session.yml file
     *
     * @return string
     */
    // protected function getDefaultSessionDriver(): string
    // {
    //     return $this->getDefaultSettings($this->getSession());
    // }


}