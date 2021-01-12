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

namespace MagmaCore\Http\Event;

use MagmaCore\EventDispatcher\Event;
//use MagmaCore\Http\RequestHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class BaseEvent extends Event
{

    private Request $request;
    private $requestType;
    protected const BASE_REQUEST = 1;

    /**
     * @param int $requestType The request type the kernel is currently processing; one of
     *                         HttpKernelInterface::MASTER_REQUEST or HttpKernelInterface::SUB_REQUEST
     */
    public function __construct(Request $request, ?int $requestType)
    {
        $this->request = $request;
        $this->requestType = $requestType;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getRequestType()
    {
        return $this->requestType;
    }

    public function isBaseRequest()
    {
        return self::BASE_REQUEST ===  $this->requestType;
    }

}
