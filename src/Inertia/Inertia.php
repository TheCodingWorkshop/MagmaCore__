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

use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class Inertia implements InertiaInterface
{

    /** @var string */
    protected $rootView;

    /** @var \Twig\Environment */
    protected $engine;

    /** @var SerializerInterface */
    protected $serializer;

    /** @var array */
    protected $sharedProps = [];

    /** @var array */
    protected $sharedViewData = [];

    /** @var array */
    protected $sharedContext = [];

    /** @var string */
    protected $version = null;

    /**
     * Undocumented function
     *
     * @param string $rootView
     * @param Environment $engine
     * @param RequestStack $request
     */
    public function __construct(string $rootView, Environment $engine, RequestStack $request)
    {
        $this->engine = $engine;
        $this->rootView = $rootView;
        
    }


    public function shared(string $key, mixed $value = null): void
    {
        $this->sharedProps[$key] = $value;
    }

    public function getShared(?string $key = null)
    {
        if ($key)
            return $this->sharedProps[$key] ?? null;
    }

    public function viewData(string $key, mixed $value = null): void
    {
        $this->sharedViewData[$key] = $value;
    }

    public function getViewData(string $key = null)
    {
        if ($key)
            return $this->sharedViewData[$key] ?? null;
    }
    public function version(string $version): void
    {
        $this->version = $version;
    }
    public function getVersion(): string
    {
        return $this->version;
    }
    public function context(string $key, mixed $value = null): void
    {
        $this->sharedContext[$key] = $value;
    }
    public function getContext(string $key = null)
    {
        if ($key)
            return $this->sharedContext[$key] ?? null;
    }
    public function setRootView(string $rootView): void
    {
        $this->rootView = $rootView;
    }
    public function getRootView(): string
    {
        return $this->rootView;
    }

    /**
     * Undocumented function
     *
     * @param string $component
     * @param array $props
     * @param array $viewData
     * @param array $context
     * @return void
     */
    public function render(string $component, array $props = [], array $viewData = [], array $context = [])
    {
        $context = array_merge($this->sharedContext, $context);
        $viewData = array_merge($this->sharedViewData, $viewData);
        $props = array_merge($this->sharedProps, $props);
        $request = $this->requestStack->getCurrentRequest();
        $url = $request->getRequestUri();

        $only = array_filter(explode(',', $request->headers->get('X-Inertia-Partial-Data')));
        $props = ($only && $request->headers->get('X-Inertia-Partial-Component') === $component) ? $this->array_only($props, $only) : $props;

        array_walk_recursive($props, function(&$props) {
            if ($props instanceof \Closure) {
                $props = $props();
            }
        });
        $version = $this->version;
        $page = json_encode(compact('component', 'props', 'url', 'version'));
        if ($request->headers->get('X-Inertia')) {
            return new JsonResponse($page, 200, [
                'Vary' => 'Accept',
                'X-Inertia' => true,
            ]);
        }

        $response = new Response();
        $response->setContent($this->engine->render($this->rootView, compact('page', 'viewData')));

        return $response;

    }

    private function array_only($array, $keys)
    {
        return array_intersect_key($array, array_flip((array) $keys));
    }

}