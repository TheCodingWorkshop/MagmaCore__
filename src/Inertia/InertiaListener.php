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

namespace MagmaCore\Inertia;

use MagmaCore\Http\Event\RequestEvent;
use MagmaCore\Http\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\Response;

class InertaiListener
{

    protected $inertia;
    protected $debug;

    public function __construct(InertiaInterface $inertia, bool $debug)
    {
        $this->inertia = $inertia;
        $this->debuyg = $debug;
    }

    public function onRequest(RequestEvent $event)
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

    public function onResponse(ResponseEvent $event)
    {
        if (!$event->getRequest()->headers->get('X-Inertia')) {
            return;
        }

        if ($this->debug && $event->getRequest()->isXmlHttpRequest()) {
            $event->getResponse()->headers->set('Magma-Debug-Toolbar-Replace', 1);
        }

        if ($event->getResponse()->isRedirect() && 302 === $event->getResponse()->getStatusCode() && in_array($event->getRequest()->getMethod(), ['PUT', 'PATCH', 'DELETE'])) {
            $event->getResponse()->setStatusCode(303);
        }
    }

}