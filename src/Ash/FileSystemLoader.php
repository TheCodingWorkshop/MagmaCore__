<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare (strict_types = 1);

namespace MagmaCore\Ash;

class FileSystemLoader implements TemplateLoaderInterface
{

    /**
     * Undocumented function
     *
     * @param array $paths
     * @param string $rootPath
     */
    public function __construct($paths = [], string $rootPath = null)
    {
        $this->rootPath = (null === $rootPath ? getcwd() : $rootPath) . DIRECTORY_SEPARATOR;
        if ($rootPath !== null && false !== ($realPath = realpath($rootPath))) {
            $this->rootPath = $realPath . DIRECTORY_SEPARATOR;
        }

        if ($paths) {
            $this->setPaths($path);
        }
    }

    public function setPaths($paths = [])
    {

    }

    public function getCachekey()
    {}
    public function isFresh(){}
    public function exists(){}


}