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

use Symfony\Component\HttpFoundation\Response;

class ResponseHandler
{

    /**
     * Wrapper method for symfony http response object
     *
     * @return Response
     */
    public function handler() : Response
    {
        if (!isset($response)) {
            $response = new Response();
            if ($response) {
                return $response;
            }
        }
        return false;
    }

}
