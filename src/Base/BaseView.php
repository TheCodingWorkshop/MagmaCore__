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

namespace MagmaCore\Base;

use MagmaCore\Utility\Yaml;
use MagmaCore\Ash\Error\LoaderError;
use MagmaCore\Ash\TemplateEnvironment;
use MagmaCore\Ash\Exception\FileNotFoundException;

class BaseView
{

    /**
     * Render a view template using the framework native template engine
     *
     * @param string $template
     * @param array $context
     * @return void
     * @throws LoaderError
     * @throws FileNotFoundException
     */
    public function ashRender(string $template, array $context = [])
    {
        return $this->templateRender($template, $context);
    }

    /**
     * Get the contents of a view template using the native framework template
     * engine.
     *
     * @param string $template
     * @param array $context
     * @return mixed
     * @throws LoaderError
     */
    public function templateRender(string $template, array $context = [])
    {
        static $ash = null;
        if ($ash === null) {
            $ash = new TemplateEnvironment(Yaml::file('template'), 'templates', TEMPLATE_PATH);
        }

        return $ash->view($template, $context);
    }

    /**
     * Get the contents of a view template using the native framework template
     * engine.
     *
     * @param string $template
     * @param array $context
     * @return mixed
     * @throws LoaderError
     */
    public function errorTemplateRender(string $template, array $context = [])
    {
        static $ash = null;
        if ($ash === null) {
            $ash = new TemplateEnvironment(Yaml::file('template'), 'Templates', TEMPLATE_ERROR);
        }

        return $ash->errorView($template, $context);
    }

    /**
     * Render a view template using the framework native template engine
     *
     * @param string $template
     * @param array $context
     * @return void
     * @throws LoaderError
     * @throws FileNotFoundException
     */
    public function ashRenderError(string $template, array $context = [])
    {
        return $this->errorTemplateRender($template, $context);
    }


}
