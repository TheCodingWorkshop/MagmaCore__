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

namespace MagmaCore\Base\Traits;

use MagmaCore\UserManager\Rbac\Permission\PermissionModel;
use MagmaCore\UserManager\UserModel;
use MagmaCore\Utility\Yaml;
use MagmaCore\Auth\Authorized;
use MagmaCore\Ash\TemplateExtension;
use MagmaCore\Auth\Roles\PrivilegedUser;
use MagmaCore\Base\Exception\BaseLogicException;
use MagmaCore\Base\Events\BeforeRenderActionEvent;

trait ControllerViewTrait
{

    /**
     * Template context which relies on the application owning a user and permission
     * model before providing any data to the rendered template
     *
     * @return array
     */
    private function templateModelContext(): array
    {
        if (!class_exists(UserModel::class) || !class_exists(PermissionModel::class)) {
            return array();
        }
        return array_merge(
            ['current_user' => Authorized::grantedUser()],
            ['this_id' => $this->thisRouteID()],
            ['this_route' => strtolower($this->thisRouteController())],
            ['this_action' => strtolower($this->thisRouteAction())],
            ['this_namespace' => strtolower($this->thisRouteNamespace())],
            ['privilege_user' => PrivilegedUser::getUser()],
            ['func' => new TemplateExtension($this)],
        );
    }

    /**
     * Return some global context to all rendered templates
     *
     * @return array
     */
    private function templateGlobalContext(): array
    {
        return array_merge(
            ['app' => Yaml::file('app')],
            ['menu' => Yaml::file('menu')],
            ['routes' => (isset($this->routeParams) ? $this->routeParams : [])]
        );
    }

    /**
     * Allow all routes within a controller to access a central define set of template context variable. Which uses
     * teh system session to store the current controller and validate that only the context set in a specific
     * wont be accessible from another controller routes
     *
     * controller global set in userController wont be accessible in roleController
     *
     * @return array
     */
    protected function controllerViewGlobals(): array
    {
        $currentController = $this->getSession()->set('controller', $this->routeParams['controller']);
        if ($this->thisRouteController() === $currentController) {
            return $this->controllerContext;
        }
        return array();

    }

    /**
     * Rendered template exception
     * @return void
     */
    private function throwViewException(): void
    {
        if (null === $this->templateEngine) {
            throw new BaseLogicException(
                'You can not use the render method if the build in template engine is not available.'
            );
        }

    }

    /**
     * Render a template response using Twig templating engine
     *
     * @param string $template - the rendering template
     * @param array $context - template data context
     * @return Response
     * @throws LoaderError
     * @throws BaseLogicException
     */
    public function view(string $template, array $context = [])
    {
        $this->throwViewException();
        $templateContext = array_merge(
            $this->templateGlobalContext(),
            $this->templateModelContext()
        );
        if ($this->eventDispatcher->hasListeners(BeforeRenderActionEvent::NAME)) {
            $this->dispatchEvent(
                BeforeRenderActionEvent::class.
                $this->routeParams['action'],
                [],
                $this
            );
        }
        $response = $this->response->handler();
        $request = $this->request->handler();
        $response->setCharset('ISO-8859-1');
        $response->headers->set('Content-Type', 'text/plain');
        $response->setStatusCode($response::HTTP_OK);
        $response->setContent($this->templateEngine->ashRender($template, array_merge($context, $templateContext, $this->controllerViewGlobals())));
        if ($response->isNotModified($request)) {
            $response->prepare($request);
            $response->send();
        }
    }

    /**
     * Alias of view() method
     *
     * @param string $template - the rendering template
     * @param array $context - template data context
     * @return Response
     * @throws LoaderError
     * @throws BaseLogicException
     */
    public function render(string $template, array $context = [])
    {
        return $this->view($template, $context);
    }


}