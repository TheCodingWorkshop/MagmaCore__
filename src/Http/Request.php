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

namespace MagmaCore\Http;

class Request
{

  public const METHOD_HEAD = 'HEAD';
  public const METHOD_GET = 'GET';
  public const METHOD_POST = 'POST';
  public const METHOD_PUT = 'PUT';
  public const METHOD_PATCH = 'PATCH';
  public const METHOD_DELETE = 'DELETE';
  public const METHOD_PURGE = 'PURGE';
  public const METHOD_OPTIONS = 'OPTIONS';
  public const METHOD_TRACE = 'TRACE';
  public const METHOD_CONNECT = 'CONNECT';


  // You can set the address when creating the Request object, or using the
  // setAddress() method.
  private $address;

  // Variables used for the request.
  public $userAgent = 'Mozilla/5.0 (compatible; PHP Request library)';
  public $connectTimeout = 10;
  public $timeout = 15;

  // Variables used for cookie support.
  private $cookiesEnabled = false;
  private $cookiePath;

  // Enable or disable SSL/TLS.
  private $ssl = false;

  // Request type.
  private $requestType;
  // If the $requestType is POST, you can also add post fields.
  private $postFields;

  // Userpwd value used for basic HTTP authentication.
  private $userpwd;
  // Latency, in ms.
  private $latency;
  // HTTP response body.
  private $responseBody;
  // HTTP response header.
  private $responseHeader;
  // HTTP response status code.
  private $httpCode;
  // cURL error.
  private $error;

  /**
   * Called when the Request object is created.
   */
  public function __construct(string $address = null)
  {
    // if (!isset($address)) {
    //   throw new BaseException("Error: Address not provided.");
    // }
      $this->address = $address !==null ? $address : $_SERVER['QUERY_STRING'];
    //$this->address = $address;
  }

  /**
   * Set the address for the request.
   *
   * @param string $address
   *   The URI or IP address to request.
   */
  public function setAddress($address)
  {
    $this->address = $address;
  }

  /**
   * Set the username and password for HTTP basic authentication.
   *
   * @param string $username Username for basic authentication.
   * @param string $password Password for basic authentication.
   */
  public function setBasicAuthCredentials($username, $password)
  {
    $this->userpwd = $username . ':' . $password;
  }

  /**
   * Enable cookies.
   *
   * @param string $cookie_path
   *   Absolute path to a txt file where cookie information will be stored.
   */
  public function enableCookies($cookie_path)
  {
    $this->cookiesEnabled = true;
    $this->cookiePath = $cookie_path;
  }

  /**
   * Disable cookies.
   */
  public function disableCookies()
  {
    $this->cookiesEnabled = false;
    $this->cookiePath = '';
  }

  /**
   * Enable SSL.
   */
  public function enableSSL()
  {
    $this->ssl = true;
  }

  /**
   * Disable SSL.
   */
  public function disableSSL()
  {
    $this->ssl = false;
  }

  /**
   * Set timeout.
   *
   * @param int $timeout Timeout value in seconds.
   */
  public function setTimeout($timeout = 15)
  {
    $this->timeout = $timeout;
  }

  /**
   * Get timeout.
   *
   * @return int Timeout value in seconds.
   */
  public function getTimeout()
  {
    return $this->timeout;
  }

  /**
   * Set connect timeout.
   *
   * @param int $connect_timeout Timeout value in seconds.
   */
  public function setConnectTimeout($connectTimeout = 10)
  {
    $this->connectTimeout = $connectTimeout;
  }

  /**
   * Get connect timeout.
   *
   * @return int Timeout value in seconds.
   */
  public function getConnectTimeout()
  {
    return $this->connectTimeout;
  }

  /**
   * Set a request type (by default, cURL will send a GET request).
   *
   * @param string $type GET, POST, DELETE, PUT, etc. Any standard request type will work.
   */
  public function setRequestType($type)
  {
    $this->requestType = $type;
  }

  /**
   * Set the POST fields (only used if $this->requestType is 'POST').
   *
   * @param array $fields An array of fields that will be sent with the POST request.
   */
  public function setPostFields($fields = array())
  {
    $this->postFields = $fields;
  }

  /**
   * Get the response body.
   *
   * @return string Response body.
   */
  public function getResponse()
  {
    return $this->responseBody;
  }

  /**
   * Get the response header.
   *
   * @return string Response header.
   */
  public function getHeader()
  {
    return $this->responseHeader;
  }

  /**
   * Get the HTTP status code for the response.
   *
   * @return int HTTP status code.
   *
   * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
   */
  public function getHttpCode()
  {
    return $this->httpCode;
  }

  /**
   * Get the latency (the total time spent waiting) for the response.
   *
   * @return int Latency, in milliseconds.
   */
  public function getLatency()
  {
    return $this->latency;
  }

  /**
   * Get any cURL errors generated during the execution of the request.
   *
   * @return string - An error message, if any error was given. Otherwise, empty.
   */
  public function getError()
  {
    return $this->error;
  }

  public function getPath()
  {
    if (str_contains($this->urlPath, '://')) {
      return str_replace('://', '', $this->urlPath);
    }
  }

  public function getInfo(): array
  {
    return $this->rawInfo;
  }

  /**
   * Check for content in the HTTP response body.
   *
   * This method should not be called until after execute(), and will only check
   * for the content if the response code is 200 OK.
   *
   * @param string $content String for which the response will be checked.
   * @return bool
   *   TRUE if $content was found in the response, FALSE otherwise.
   */
  public function checkResponseForContent($content = ''): bool
  {
    if ($this->httpCode == 200 && !empty($this->responseBody)) {
      if (strpos($this->responseBody, $content) !== FALSE) {
        return TRUE;
      }
    }
    return FALSE;
  }

  public function getBody(): array
  {
      $body = [];
      if ($this->requestType === self::METHOD_GET) {
          foreach ($_GET as $key => $value) {
            $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
          }
      } elseif ($this->requestType === self::METHOD_POST) {
          foreach ($_POST as $key => $value) {
            $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
          }
      }

      return $body;
  }

  /**
   * Undocumented function
   *
   * @param array $body
   * @param string|null $username
   * @param string|null $password
   * @return self
   */
  public function post(mixed $body = null, string $username = null, string $password = null)
  {
    $this->setRequestType(self::METHOD_POST);

    if ($username !==null && $password !==null)
      $this->setBasicAuthCredentials($username, $password);
    if ($body !==null)
      $this->setPostFields($body ?? $this->getBody());

    $this->execute();
    return $this;
  }

  /**
   * Undocumented function
   *
   * @param string|null $username
   * @param string|null $password
   * @return self
   */
  public function get(string $username = null, string $password = null): self
  {
    $this->setRequestType(self::METHOD_GET);

    if ($username !==null && $password !==null)
    $this->setBasicAuthCredentials($username, $password);

    $this->execute();
    return $this;

  }

  /**
   * Undocumented function
   *
   * @param mixed $body
   * @param string|null $username
   * @param string|null $password
   * @return self
   */
  public function patch(mixed $body = null, string $username = null, string $password = null): self
  {
    $this->setRequestType(self::METHOD_PATCH);

    if ($username !==null && $password !==null)
    $this->setBasicAuthCredentials($username, $password);
    if ($body !==null)
      $this->setPostFields($body ?? $this->getBody());

    $this->execute();
    return $this;

  }

  /**
   * Undocumented function
   *
   * @param mixed $body
   * @param string|null $username
   * @param string|null $password
   * @return self
   */
  public function put(mixed $body = null, string $username = null, string $password = null): self
  {
    $this->setRequestType(self::METHOD_PUT);

    if ($username !==null && $password !==null)
    $this->setBasicAuthCredentials($username, $password);
    if ($body !==null)
      $this->setPostFields($body ?? $this->getBody());

    $this->execute();
    return $this;

  }

  /**
   * Undocumented function
   *
   * @param mixed $body
   * @param string|null $username
   * @param string|null $password
   * @return self
   */
  public function delete(mixed $body = null, string $username = null, string $password = null): self
  {
    $this->setRequestType(self::METHOD_DELETE);

    if ($username !==null && $password !==null)
    $this->setBasicAuthCredentials($username, $password);
    if ($body !==null)
      $this->setPostFields($body ?? $this->getBody());

    $this->execute();
    return $this;

  }

  /**
   * Check a given address with cURL.
   *
   * After this method is completed, the response body, headers, latency, etc.
   * will be populated, and can be accessed with the appropriate methods.
   */
  public function execute()
  {
    // Set a default latency value.
    $latency = 0;

    // Set up cURL options.
    $ch = curl_init();
    // If there are basic authentication credentials, use them.
    if (isset($this->userpwd)) {
      curl_setopt($ch, CURLOPT_USERPWD, $this->userpwd);
    }
    // If cookies are enabled, use them.
    if ($this->cookiesEnabled) {
      curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiePath);
      curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiePath);
    }
    // Send a custom request if set (instead of standard GET).
    if (isset($this->requestType)) {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->requestType);
      // If POST fields are given, and this is a POST request, add fields.
      //if ($this->requestType == 'POST' && isset($this->postFields)) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postFields);
      //
    }
    // Don't print the response; return it from curl_exec().
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $this->encodeUrl($this->address));
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
    // Follow redirects (maximum of 5).
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    // SSL support.
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl);
    // Set a custom UA string so people can identify our requests.
    curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
    // Output the header in the response.
    curl_setopt($ch, CURLOPT_HEADER, true);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
    $rawInfo = curl_getInfo($ch);
    curl_close($ch);

    // Set the header, response, error and http code.
    $this->responseHeader = substr((string)$response, 0, $header_size);
    $this->responseBody = substr((string)$response, $header_size);
    $this->error = $error;
    $this->httpCode = $http_code;
    $this->urlPath = $rawInfo['url'];
    $this->rawInfo = $rawInfo;

    // Convert the latency to ms.
    $this->latency = round($time * 1000);

  }

  private function getArrayFromQuerystring($query)
    {
        $query = preg_replace_callback('/(?:^|(?<=&))[^=[]+/', function ($match) {
            return bin2hex(urldecode($match[0]));
        }, $query);

        parse_str($query, $values);

        return array_combine(array_map('hex2bin', array_keys($values)), $values);
    }

   /**
     * Ensure that a URL is encoded and safe to use with cURL
     * @param  string $url URL to encode
     * @return string
     */
    private function encodeUrl($url)
    {
        $url_parsed = parse_url($url);

        $scheme = $url_parsed['scheme'] . '://';
        $host   = $url_parsed['host'];
        $port   = (isset($url_parsed['port']) ? $url_parsed['port'] : null);
        $path   = (isset($url_parsed['path']) ? $url_parsed['path'] : null);
        $query  = (isset($url_parsed['query']) ? $url_parsed['query'] : null);

        if ($query !== null) {
            $query = '?' . http_build_query($this->getArrayFromQuerystring($query));
        }

        if ($port && $port[0] !== ':') {
            $port = ':' . $port;
        }

        $result = $scheme . $host . $port . $path . $query;
        return $result;
    }
}
