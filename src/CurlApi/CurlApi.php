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

namespace MagmaCore\CurlApi;

use MagmaCore\CurlApi\Actions\CurlCreate;
use MagmaCore\CurlApi\Actions\CurlRead;
use MagmaCore\CurlApi\Actions\CurlUpdate;
use MagmaCore\CurlApi\Actions\CurlDelete;
use MagmaCore\CurlApi\Actions\CurlShow;
use MagmaCore\CurlApi\Actions\CurlEdit;
use MagmaCore\CurlApi\Exception\CurlException;

class CurlApi implements CurlApiInterface
{

    protected mixed $data = null;
    protected array $headers = ["User-Agent:", "Authorization:"];
    protected ?string $path = null;
    protected int $statusCode;
    private $ch;

//    private $options = array(
//    'CURLOPT_RETURNTRANSFER' => true,         // return web page
//    'CURLOPT_HEADER'         => false,        // don't return headers
//    'CURLOPT_FOLLOWLOCATION' => true,         // follow redirects
//    'CURLOPT_ENCODING'       => "",           // handle all encodings
//    'CURLOPT_USERAGENT'      => "spider",     // who am i
//    'CURLOPT_AUTOREFERER'    => true,         // set referer on redirect
//    'CURLOPT_CONNECTTIMEOUT' => 120,          // timeout on connect
//    'CURLOPT_TIMEOUT'        => 120,          // timeout on response
//    'CURLOPT_MAXREDIRS'      => 10,           // stop after 10 redirects
//    'CURLOPT_POST'            => 1,            // i am sending post data
//    'CURLOPT_POSTFIELDS'     => $curl_data,    // this are my post vars
//    'CURLOPT_SSL_VERIFYHOST' => 0,            // don't verify ssl
//    'CURLOPT_SSL_VERIFYPEER' => false,        //
//    'CURLOPT_VERBOSE'        => 1                //
//    );

    /**
     * @param string|null $curlActionString
     * @param array|string|null $data
     * @param array $headers
     */
    public function __construct(string $path = null, mixed $data = null, array $headers = [])
    {
        if ($path === null || $path === '') {
            throw new CurlException('No resource endpoint was passed. Please specify your API endpoint.');
        }
        $this->path = $path;
        $this->data = $data ?? NULL;
        $this->headers = (count($headers) > 0 ? array_merge($this->headers, $headers) : $this->headers);
        /* initialize a curl session */
        $this->ch = curl_init();

        curl_setopt_array($this->ch, [CURLOPT_HTTPHEADER => $this->headers, CURLOPT_RETURNTRANSFER => true]);
        curl_setopt($this->ch, CURLOPT_URL, $this->path);

    }

    public function response(): self
    {
        $this->response = curl_exec($this->ch);
        return $this;
    }

    public function onClose(): self
    {
        curl_close($this->ch);
        return $this;
    }

    private function status()
    {
        return curl_getinfo($this->ch, CURLINFO_RESPONSE_CODE);

    }

    public function error()
    {
        $errno = curl_errno($this->ch);
        $this->headers['errno'] = $errno;
        return $this->headers;
    }

    public function errorMsg()
    {
        $error = curl_error($this->ch);
        $this->headers['error'] = $error;
        return $this->headers;
    }

    public function hasStatus()
    {
        if ($this->statusCode() === 422) {

        }
        if ($this->statusCode() !==201) {
            var_dump($this->repository);
            echo "Unexpected status code: " .  $this->statusCode();
            exit;
        }
    }

    /**
     * @param $response
     * @return mixed
     */
    public function exec($response = null): mixed
    {
        $encode = $response !==null ? json_encode($response) : $this->response;
        return json_decode($encode, true);
    }

    public function create(): self
    {
        (new CurlCreate($this))
            ->endpointCreate($this->ch, $this->path, $this->data ?? null, $this->headers ?? []);
        return $this;
    }

    public function read(): self
    {
        (new CurlRead())
            ->endpointRead($this->ch, $this->path, $this->data ?? null, $this->headers ?? []);
        return $this;
    }

    public function update(): self
    {
        (new CurlUpdate($this))
            ->endpointUpdate($this->ch, $this->path, $this->data ?? null, $this->headers ?? []);
        return $this;
    }

    public function delete(): self
    {
        (new CurlDelete($this))
            ->endpointDelete($this->ch, $this->path, $this->data ?? null, $this->headers ?? []);
        return $this;
    }

    public function show(): self
    {
        (new CurlShow($this))
            ->endpointShow($this->ch, $this->path, $this->data ?? null, $this->headers ?? []);
        return $this;

    }

    public function edit(): self
    {
        (new CurlEdit($this))
            ->endpointEdit($this->ch, $this->path, $this->data ?? null, $this->headers ?? []);
        return $this;

    }

    public function callCurlApi(string $method = null, string $url = null, mixed $data = null, array $headers = [])
    {
        $curl = curl_init();
        $headers = (count($headers) > 0 ? array_merge($this->headers, $headers) : $this->headers);

        curl_setopt_array($curl, [CURLOPT_HTTPHEADER => $headers, CURLOPT_RETURNTRANSFER => true]);
        curl_setopt($curl, CURLOPT_URL, $url);

        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "DELETE":
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

}