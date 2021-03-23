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

use MagmaCore\Ash\Exception\FileNotFoundException;

trait TemplateTraits
{

    /**
     * Add one or more js file. Can be used within the layout.html template to add template
     * js files. These are loaded where ever the function is called. Uses internal method
     * to resolve the location of the files and will throw an exception if file is not found.
     *
     * @param mixed $css
     * @return string
     * @throws FileNotFoundException
     */
    public function addjs($js)
    {
        $this->js = $js;
        if (is_array($this->js) && count($this->js) > 0) {
            foreach ($this->js as $file) {
                $jsFile = $this->resolvePath($file);
                if ($jsFile) {
                    echo '<script src="' . $jsFile . '"></script>' . "\n";
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
    public function addcss($css)
    {
        $this->css = $css;
        if (is_array($this->css) && count($this->css) > 0) {
            foreach ($this->css as $file) {
                $cssFile = $this->resolvePath($file, 'css');
                if ($cssFile) {
                    echo '<link rel="stylesheet" href="' . $cssFile . '">' . "\n";
                }
            }
        }
    }


    /**
     * throw a file not found exception is the file being loaded doesn't exists.
     *
     * @param string $file
     * @param string $type - defaults to a javascript file can be change within other methods
     * @return void
     */
    private function resolvePath(string $file, string $type = 'js'): string
    {
        $pathFile = $this->assignedPath($type) . "/{$file}.{$type}";
        if (!file_exists(APP_ROOT . $pathFile)) {
            throw new FileNotFoundException("{$file} was not found within the specified directory. Please ensure your file exists."); 
        }
        return $pathFile;
    }

    /**
     * Returns returns the relative path to the script static files
     *
     * @param string $path
     * @return string
     */
    private function assignedPath(string $path = 'js'): string
    {
        return ASSET_PATH . '/' . $path;
    }

}