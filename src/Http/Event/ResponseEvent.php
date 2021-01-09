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

namespace MagmaCore\EventDispatcher\Event;

use MagmaCore\Http\RequestHandler as Request;
use MagmaCore\Http\ResponseHandler as Response;
use MagmaCore\Http\Event\BaseEvent;

/**
 * Allows to filter a Response object.
 *
 * You can call getResponse() to retrieve the current response. With
 * setResponse() you can set a new response that will be returned to the
 * browser.
 * @credit Symfony
 */
final class ResponseEvent extends BaseEvent
{
    private $response;

    public function __construct(Request $request, int $requestType, Response $response)
    {
        parent::__construct($request, $requestType);
        $this->setResponse($response);
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }
}
