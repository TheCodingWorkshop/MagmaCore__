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

namespace MagmaCore\Ash\Traits;

use Exception;
use MagmaCore\Utility\Yaml;
use MagmaCore\Ash\Exception\FileNotFoundException;

trait TemplateTraits
{

    /**
     * Add one or more js file. Can be used within the layout.html template to add template
     * js files. These are loaded where ever the function is called. Uses internal method
     * to resolve the location of the files and will throw an exception if file is not found.
     *
     * @param mixed|null $js
     * @param string $location
     * @return string
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function addjs(mixed $js = null, string $location = 'footer'): void
    {
        $this->js = $js;
        $jsYmls = Yaml::file('assets')['scripts'];
        $allJs = array_merge($jsYmls, $this->js ?? []);
        if (is_array($allJs) && count($allJs) > 0) {
            foreach ($allJs as $file) {
                if (isset($file['enable']) && $file['enable'] === true) {
                    if (isset($file['location']) && $file['location'] === $location) {
                        if (isset($file['cdn']) && $file['cdn'] === true) {
                            $jsFile = $file['src'] ?? '';
                        } else {
                            $jsFile = $this->resolvePath($file['src']);
                        }
                        if ($jsFile) {
                            //$minifier = new Minify\CSS($jsFile);
                            $script = '<script>';
                            $script .= ' src="' . $jsFile . '"';
                            $script .= isset($file['reload']) && $$file['reload'] === true ? ' data-turbo-track="reload"' : '';
                            echo '<script src="' . $jsFile . '" data-turbo-track="reload"></script>' . "\n";
                        }
                    }
                }
            }
        }
    }

    /**
     * Add one or more css file. Can be used within the layout.html template to add template
     * css files. These are loaded where ever the function is called. Uses internal method
     * to resolve the location of the files and will throw an exception if file is not found.
     *
     * @param mixed $css
     * @return string
     * @throws FileNotFoundException
     */
    public function addcss(mixed $css = null): void
    {
        $this->css = $css;
        $cssYmls = Yaml::file('assets')['stylesheets'];
        $allCss = array_merge($cssYmls, $this->css ?? []);
        if (is_array($allCss) && count($allCss) > 0) {

            foreach ($allCss as $file) {
                if (isset($file['enable']) && $file['enable'] === true) {
                    if (isset($file['cdn']) && $file['cdn'] === true) {
                        $cssFile = $file['href'] ?? '';
                    } else {
                        $cssFile = $this->resolvePath($file['href']);
                    }

                    if ($cssFile) {
                        $css = '<link rel="stylesheet"';
                        $css .= ' href="' . $cssFile . '"';
                        $css .= isset($file['reload']) && $file['reload'] === true ? ' data-turbo-frame="reload"' : '';
                        $css .= '>' . "\n";
                        echo $css;
                    }
                }
            }
        }
    }


    /**
     * throw a file not found exception is the file being loaded doesn't exists.
     *
     * @param string $file
     * @return string
     * @throws FileNotFoundException
     */
    private function resolvePath(string $file): string
    {
        if (!file_exists(APP_ROOT . $file)) {
            throw new FileNotFoundException("{$file} was not found within the specified directory. Please ensure your file exists.");
        }
        return $file;
    }
}
