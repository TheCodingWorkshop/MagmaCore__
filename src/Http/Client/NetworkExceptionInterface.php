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

namespace MagmaCore\Http\Client;

use MagmaCore\Http\Message\RequestInterface;
use MagmaCore\Http\Client\ClientExceptionInterface;

/**
 * Thrown when the request cannot be completed because of network issues.
 * There is no response object as this exception is thrown when no response has been received.
 * Example: the target host name can not be resolved or the connection failed.
 */
interface NetworkExceptionInterface extends ClientExceptionInterface
{

    /**
     * Returns the request.
     *
     * The request object MAY be a different object from the one passed to ClientInterface::sendRequest()
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface;
}
