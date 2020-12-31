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

class CookieEnvironment
{

    /** @var string - the current stable cookie version */
    protected const COOKIE_VERSION = '1.0.0';

    /** @var array */
    protected array $cookieConfig;

    /**
     * Main class constructor
     * 
     * @param array $cookieConfig
     * @return void
     */
    public function __construct(array $cookieConfig)
    {
        if (count($cookieConfig) < 0 || !is_array($cookieConfig)) {
            throw new \LogicException('Session environment has failed to load. Ensure your are passing the correct yaml configuration file to the session facade class object');
        }
        $this->cookieConfig = $cookieConfig;
    }

    /**
     * An associative array which may have any of the keys expires, path, domain, 
     * secure, httponly and samesite. If any other key is present an error of level E_WARNING is
     * generated. The values have the same meaning as described for the parameters with 
     * the same name. The value of the samesite element should be either None, Lax or Strict. 
     * If any of the allowed options are not given, their default values are the same as the 
     * default values of the explicit parameters. If the samesite element is omitted, 
     * no SameSite cookie attribute is set.
     * 
     * @return array
     */
    public function getConfig() : array
    {
        return $this->cookieConfig;
    }

    /**
     * The time the cookie expires. This is a Unix timestamp so is in number of seconds 
     * since the epoch. In other words, you'll most likely set this with the time() 
     * function plus the number of seconds before you want it to expire. Or you might 
     * use mktime(). time()+60*60*24*30 will set the cookie to expire in 30 days. 
     * If set to 0, or omitted, the cookie will expire at the end of the session 
     * (when the browser closes).
     * 
     * @return int
     */
    public function getExpiration() : int
    {
        $expires = (isset($this->getConfig()['expires']) ? filter_var($this->getConfig()['expires'], FILTER_VALIDATE_INT) : 0);
        if ($expires) {
            return $expires;
        }
    }
    
    /**
     * The path on the server in which the cookie will be available on. If set to '/', 
     * the cookie will be available within the entire domain. If set to '/foo/', 
     * the cookie will only be available within the /foo/ directory and all 
     * sub-directories such as /foo/bar/ of domain. The default value is the 
     * current directory that the cookie is being set in.
     * 
     * @return string
     */
    public function getPath() : string
    {
        $path = (isset($this->getConfig()['path']) ? filter_var($this->getConfig()['path'], FILTER_SANITIZE_STRING) : '/');
        if ($path) {
            return $path;
        }
    }

    /**
     * The (sub)domain that the cookie is available to. Setting this to a subdomain 
     * (such as 'www.example.com') will make the cookie available to that subdomain 
     * and all other sub-domains of it (i.e. w2.www.example.com). To make the cookie 
     * available to the whole domain (including all subdomains of it), simply set the 
     * value to the domain name ('example.com', in this case).
     * 
     * @return string
     */
    public function getDomain() : string
    {
        $domain = (isset($this->getConfig()['domain']) ? $this->getConfig()['domain'] : isset($_SERVER['SERVER_NAME']));
        if ($domain) {
            return $domain;
        }

    }

    /**
     * Indicates that the cookie should only be transmitted over a secure HTTPS 
     * connection from the client. When set to TRUE, the cookie will only be set if a 
     * secure connection exists. On the server-side, it's on the programmer to send 
     * this kind of cookie only on secure connection (e.g. with respect to $_SERVER["HTTPS"]).
     * 
     * @return bool
     */
    public function isSecure() : bool
    {
        $isSecure = (isset($this->getConfig()['secure']) ? $this->getConfig()['secure'] : isset($_SERVER['HTTPS']));
        if (is_bool($isSecure)) {
            return $isSecure;
        }

    }

    /**
     * When TRUE the cookie will be made accessible only through the HTTP protocol. 
     * This means that the cookie won't be accessible by scripting languages, 
     * such as JavaScript. It has been suggested that this setting can effectively 
     * help to reduce identity theft through XSS attacks (although it is not supported 
     * by all browsers), but that claim is often disputed. TRUE or FALSE
     * 
     * @return null|bool
     */
    public function isHttpOnly() : bool
    {
        $isHttpOnly = (isset($this->getConfig()['httpOnly']) ? $this->getConfig()['httpOnly'] : true);
        if (is_bool($isHttpOnly)) {
            return $isHttpOnly;
        }

    }

    /**
     * Get the unique session identifier
     * 
     * @return string
     */
    public function getCookieName() : string
    {
        $cookieName = (isset($this->getConfig()['name']) ? $this->getConfig()['name'] : '');
        if ($cookieName) {
            return $cookieName;
        }
    }   

}