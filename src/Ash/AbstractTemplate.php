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

use MagmaCore\Ash\TemplateInterface;

abstract class AbstractTemplate implements TemplateInterface
{
    /** @var array template blocks definitions */
    protected $blocks = array();
    /** @var array - template configurations */
    protected array $templateEnv;

    /**
     * Undocumented function
     *
     * @param array $templateEnvironment
     * @return void
     */
    public function __construct(array $templateEnvironment)
    {
        $this->templateEnv = $templateEnvironment;
    }

    /**
     * Undocumented function
     *
     * @param string $file
     * @return void
     */
    public function cache(string $file)
    {
        /** Create the cache directory if it doesn't exists */
        $templateEnv = $this->templateEnv['template']['template_cache'];
        $cachePath = TEMPLATES . 'cache/';
        if (!file_exists($cachePath)) {
            mkdir($cachePath, 0744);
        }
        /* rename the cache to match the name of the template */
        $fileCache = $cachePath . str_replace(array('/', ':', '.html'), array('_', '', ''), $file . '.php');
        if (!$templateEnv['enable'] || !file_exists($fileCache) || filemtime($fileCache) < filemtime($file)) {
            $code = $this->fileIncludes($file);
            $code = $this->codeCompiler($code);    
            file_put_contents($fileCache, '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL . $code);

        }
        return $fileCache;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function clearCache()
    {
        foreach (glob($this->templateEnv['template']['template_cache']['path'] . '*') as $file) {
            unlink($file);
        }
    }

    /**
     * Undocumented function
     *
     * @param mixed $code
     * @return void
     */
    public function codeCompiler(mixed $code)
    {
        if ($code) {
            $code = $this->blockCompiler($code);
            $code = $this->yieldCompiler($code);
            $code = $this->escapedEchosCompiler($code);
            $code = $this->echosCompiler($code);
            $code = $this->phpCompiler($code);
            return $code;
        }
        return false;
    }

    /**
     * Undocumented function
     *
     * @param string $file
     * @return void
     */
    public function fileIncludes(string $file)
    {
        if ($file) {
            $code = file_get_contents($file);
            preg_match_all('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
            foreach ($matches as $value) {
                /* Pass the template directory to the method so the file can be found */
                $code = str_replace($value[0], $this->fileIncludes(TEMPLATES . $value[2]), $code);
            }
            $code = preg_replace('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', '', $code);
            return $code;
        }
    }

    /**
     * Undocumented function
     *
     * @param mixed $code
     * @return void
     */
    public function phpCompiler(mixed $code)
    {
        return preg_replace('~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $code);
    }

    /**
     * Undocumented function
     *
     * @param mixed $code
     * @return void
     */
    public function echosCompiler(mixed $code)
    {
        return preg_replace('~\{{\s*(.+?)\s*\}}~is', '<?php echo $1 ?>', $code);
    }

    /**
     * Undocumented function
     *
     * @param mixed $code
     * @return void
     */
    public function escapedEchosCompiler(mixed $code)
    {
        return preg_replace('~\{{{\s*(.+?)\s*\}}}~is', '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $code);
    }

    /**
     * Undocumented function
     *
     * @param mixed $code
     * @return void
     */
    public function blockCompiler(mixed $code)
    {
        preg_match_all('/{% ?block ?(.*?) ?%}(.*?){% ?endblock ?%}/is', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            if (!array_key_exists($value[1], $this->blocks)) {
                $this->blocks[$value[1]] = '';
            }
            if (!strpos($value[2], '@parent') === false) {
                $this->blocks[$value[1]] = $value[2];
            } else {
                $this->blocks[$value[1]] = str_replace('@parent', $this->blocks[$value[1]], $value[2]);
            }
            $code = str_replace($value[0], '', $code);
        }
        return $code;
    }

    /**
     * Undocumented function
     *
     * @param mixed $code
     * @return void
     */
    public function yieldCompiler(mixed $code)
    {
        foreach ($this->blocks as $block => $value) {
            $code = preg_replace('/{% ?yield ?' . $block . ' ?%}/', $value, $code);
        }
        $code = preg_replace('/{% ?yield ?(.*?) ?%}/i', '', $code);
        return $code;

    }

}
