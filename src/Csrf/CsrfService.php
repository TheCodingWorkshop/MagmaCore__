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

namespace MagmaCore\Csrf;

use function is_null;

class CsrfService
{

    protected ?string $excludeUrl = null;

    /**
     * Undocumented function
     *
     * @param string|null $excludeUrl
     * @param array|null $post
     * @param array|null $session
     * @param array|null $server
     */
    public function __construct(string $excludeUrl = null, array $post = null, array $session = null, array $server = null)
    {
        
        if (!is_null($excludeUrl)) {
            $this->excludeUrl = $excludeUrl;
        }
        if (!is_null($post)) {
            $this->post = $post;
        } else {
            $this->post = $_POST;
        }

        if (!is_null($session)) {
            $this->session = $session;
        } elseif (!is_null($_SESSION) && isset($_SESSION)) {
            $this->session = $_SESSION;
        } else {
            throw new \Error('No session available for persistence');
        }

        if (!is_null($server)) {
            $this->server = $server;
        } else {
            $this->server = $_SERVER;
        }


    }

    public function getCsrfField(): void
    {
        $csrfToken = $this->getCsrfToken();
        echo sprintf('<!--\n--><input type="hidden" name="%s" value="%s"/>', 
            $this->xssafe($this->formTokenLabel),
            $this->xssafe($csrfToken)
        );
    }

    /**
     * Undocumented function
     *
     * @param [type] $data
     * @param string $encoding
     * @return string
     */
    private function xssafe($data, string $encoding = 'UTF-8'): string
    {
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding);
    }

    /**
     * Generate, store, and return the CSRF token
     *
     * @return string[]
     */
    public function getCSRFToken()
    {
        if (empty($this->session[$this->sessionTokenLabel])) {
            $this->session[$this->sessionTokenLabel] = bin2hex(openssl_random_pseudo_bytes(32));
        }

        if ($this->hmac_ip !== false) {
            $token = $this->hMacWithIp($this->session[$this->sessionTokenLabel]);
        } else {
            $token = $this->session[$this->sessionTokenLabel];
        }
        return $token;
    }


}