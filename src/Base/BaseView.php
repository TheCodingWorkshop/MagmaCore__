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

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use MagmaCore\Utility\Yaml;
use MagmaCore\Twig\TwigExtension;

class BaseView
{ 

    /**
     * Render a view template using Twig
     *
     * @param string $template The template file
     * @param array $args Associative array of data to display in the view (optional)
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function twigRender($template, $args = [])
    {
        echo $this->getTemplate($template, $args);
    }

    /**
     * Get the contents of a view template using Twig
     *
     * @param string $template The template file
     * @param array $args Associative array of data to display in the view (optional)
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function getTemplate(string $template, array $args = [], string $directory = TEMPLATE_PATH)
    {
        static $twig = null;
        if ($twig === null) {
            $loader = new FilesystemLoader('templates', $directory);
            $twig = new Environment($loader, [Yaml::file('twig')]);

            $twig->addExtension(new DebugExtension());
            $twig->addExtension(new TwigExtension());
        }

        return $twig->render($template, $args);
    }

    /**
     * Undocumented function
     *
     * @param string $template
     * @param array $args
     * @param string $directory
     * @return void
     */
    public function  getErrorResource(
        string $template, 
        array $args = [], 
        string $directory = ERROR_RESOURCE) 
    {
        static $twig = null;
        if ($twig === null) {
            $loader = new FilesystemLoader('templates', $directory);
            $twig = new Environment($loader, [Yaml::file('twig')]);

            $twig->addExtension(new DebugExtension());
            //$twig->addExtension(new TwigExtension());
        }

        return $twig->render($template, $args);
    }


}
