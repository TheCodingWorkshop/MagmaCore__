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

namespace MagmaCore\CurlApi\Actions;

use MagmaCore\CurlApi\CurlTrait;

class CurlDelete
{

    /**
     * Curl RestAPi for deleting a resource
     * @param $ch
     * @param string $path
     * @param mixed|null $data
     * @param array $headers
     * @return $this
     */
    public function endpointDelete($ch, string $path, mixed $data = null, array $headers = []): self
    {
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        return $this;
    }

}
