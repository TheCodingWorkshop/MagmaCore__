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

use MagmaCore\Ash\Exception\FileNotFoundException;

abstract class AbstractTemplate implements TemplateInterface
{
    /** @var array template blocks definitions */
    protected array $blocks = array();
    /** @var array|TemplateEnvironment - template configurations */
    protected TemplateEnvironment|array $templateEnv;

    /**
     * Main constructor class
     *
     * @param TemplateEnvironment $templateEnvironment
     * @return void
     */
    public function __construct(TemplateEnvironment $templateEnvironment)
    {
        $this->templateEnv = $templateEnvironment;
    }

    /**
     * Load template from the cache file directory.
     * 
     *
     * @param string $file
     * @return mixed
     */
    public function cache(string $file): mixed
    {
        $fileCache = $this->templateEnv->getCacheKey($file);
        if (!$this->templateEnv->getCacheStatus() ||
            !file_exists($fileCache) || filemtime($fileCache) < filemtime($file)) {
            $code = $this->fileIncludes($file);
            $code = $this->codeCompiler($code);
            file_put_contents($fileCache, '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL . $code);
        }
        return $fileCache;

    }

    /**
     * Force the clearance of the cache directory. This ultimately deletes all the 
     * cache files from the cache directory and rebuild with updated template 
     * content if any.
     *
     * @return void
     */
    public function clearCache(): void
    {
        foreach (glob($this->templateEnv['template']['template_cache']['path'] . '*') as $file) {
            unlink($file);
        }
    }

    /**
     * MagmaCore generic code compiler. compile regular php code, template extensions
     * within various different placeholders {} {%%} {{}} {{{}}}
     *
     * @param mixed $code
     * @return string|array|bool|null
     */
    public function codeCompiler(mixed $code): string|array|bool|null
    {
        if ($code) {
            $code = $this->blockCompiler($code);
            $code = $this->yieldCompiler($code);
            $code = $this->functionEchosCompiler($code);
            $code = $this->escapedEchosCompiler($code);
            $code = $this->variableCompiler($code);
            $code = $this->echosCompiler($code);
            return $this->phpCompiler($code);
        }        
        return false;
    }

    /**
     * Parse extends and include blocks within the rendered template.
     *
     * @param string $file
     * @return string|array|null
     */
    public function fileIncludes(string $file): string|array|null
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException('Your ' . $file . ' does not exists within the specified directory.');
        }
        if ($file) {
            $code = file_get_contents($file);
            preg_match_all('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
            foreach ($matches as $value) {
                /* Pass the template directory to the method so the file can be found */
                $code = str_replace($value[0], $this->fileIncludes(TEMPLATES . $value[2]), $code);
            }
            return preg_replace('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', '', $code);
        }
    }

    /**
     * Compile native php functions using the curly braces and percentage symbol
     * {% array_key_exists(arguments) %} to execute php code
     *
     * @param mixed $code
     * @return array|string|null
     */
    public function phpCompiler(mixed $code): array|string|null
    {
        return preg_replace('~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $code);
    }

    /**
     * Compile php variables
     *
     * @param mixed $code
     * @return array|string|null
     */
    public function variableCompiler(mixed $code): array|string|null
    {
        return preg_replace('~\{::\s*(.+?)\s*\}~is', '$1', $code);
    }

    /**
     * Compile native php functions within the HTML template file using double curly
     * braces {{ function }}. This will simple echo out the function. ie can using 
     * php function like 
     *
     * @param mixed $code
     * @return array|string|null
     */
    public function echosCompiler(mixed $code): array|string|null
    {
        return preg_replace('~\{{\s*(.+?)\s*\}}~is', '<?php echo $1 ?>', $code);
    }

    /**
     * Compile the extension methods within the html templates by wrapping tge method
     * in {} curl braces. with space around the method { method(arguments) }. Template
     * method cane be extended by extending the base TemplateExtension class
     *
     * @param mixed $code
     * @return array|string|null
     */
    public function functionEchosCompiler(mixed $code): array|string|null
    {
        return preg_replace('~\{@ \s*(.+?)\s*\ @}~is', '<?php echo $func->$1 ?>', $code);
    }

    /**
     * Compile native code using triple curly braces {{{ code }}}. using these
     * triple curly braces uses php html entities which will convert some
     * characters to HTML entities like so
     * 
     * &lt;a href=&quot;&quot;&gt;&lt;/a&gt;
     * <a href=""></a>
     * 
     * @param mixed $code
     * @return array|string|null
     */
    public function escapedEchosCompiler(mixed $code): array|string|null
    {
        return preg_replace('~\{{{\s*(.+?)\s*\}}}~is', '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $code);
    }

    /**
     * Allow content to be wrap in some special {block} syntax
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
     * Yield/Generate the content for which to display
     *
     * @param mixed $code
     * @return array|string|null
     */
    public function yieldCompiler(mixed $code): array|string|null
    {
        foreach ($this->blocks as $block => $value) {
            $code = preg_replace('/{% ?yield ?' . $block . ' ?%}/', $value, $code);
        }
        return preg_replace('/{% ?yield ?(.*?) ?%}/i', '', $code);

    }

}