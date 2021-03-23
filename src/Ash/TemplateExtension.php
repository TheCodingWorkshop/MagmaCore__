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

namespace MagmaCore\Ash;

use MagmaCore\Utility\Stringify;
use MagmaCore\Ash\Traits\TemplateTraits;
use MagmaCore\Ash\Exception\TemplateExtensionInvalidArgumentException;

class TemplateExtension
{

    /** @var trait - holds common function used across template extensions */
    use TemplateTraits;

    /** @var array */
    protected array $js = [];
    /** @var array */
    protected array $css = [];
    /** @var string */
    protected string $string;


    public function __construct()
    {
        
    }

    /**
     * Undocumented function
     *
     * @param string $string
     * @return static
     */
    public function lang(string $string): static
    {
        $this->string = $string;
        return $this;
    }

    public function isLangValid()
    {
        if (is_string($this->string) && (!empty($this->string))) {
            return $this->string;
        } else {
            throw new TemplateExtensionInvalidArgumentException('Invalid');
        }
    }

    public function caps(): string
    {
        $this->isLangValid();
        $this->string = Stringify::capitalize($this->string);
        return $this->string;
    
    }

    public function lower(): string
    {
        $this->isLangValid();
        $this->string = strtolower($this->string);
        return $this->string;
    
    }

    public function upper(): string
    {
        $this->isLangValid();
        $this->string = strtoupper($this->string);
        return $this->string;
    
    }

    public function plural(): string
    {
        $this->isLangValid();
        $this->string = Stringify::pluralize($this->string);
        return $this->string;
    
    }
    public function justify($atts = 'ucwords'): string
    {
        $this->isLangValid();
        $this->string = Stringify::justify($this->string, $atts);
        return $this->string;
    
    }

    public function replace(mixed $search, mixed $replace)
    {
        $this->isLangValid();
        $this->string = str_replace($search, $replace, $this->string);
        return $this->string;
    }



}