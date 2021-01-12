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

namespace MagmaCore\Inertia\EventListener;

use MagmaCore\Inertia\Service\InertiaInterface;
use MagmaCore\Http\Event\RequestEvent;
use MagmaCore\Http\Event\ResponseEvent;
use MagmaCore\Http\ResponseHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class InertiaListener
{

    protected $inertia;

    public function __construct(InertiaInterface $inertia)
    {
        $this->inertia = $inertia;
    }

    public function onBaseRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->headers->get('X-Inertia')) {
            return;
        }

        if ('GET' === $request->getMethod() && $request->headers->get('X-Inertia-Version') !== $this->inertia->getVersion()) {
            $response = new Response('', 409, ['X-Inertia-Location' => $request->getUri()]);
            $event->setResponse($response);
        }
    }

    public function onBaseResponse(ResponseEvent $event)
    {
        if (!$event->getRequest()->headers->get('X-Inertia')) {
            return;
        }
        if ($event->getResponse()->isRedirect()
            && 302 === $event->getResponse()->getStatusCode()
            && in_array($event->getRequest()->getMethod(), ['PUT', 'PATCH', 'DELETE'])
        ) {
            $event->getResponse()->setStatusCode(303);
        }
    }

}
