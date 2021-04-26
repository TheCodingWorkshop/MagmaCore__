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

use MagmaCore\Ash\Template;
use MagmaCore\Ash\TemplateLoaderInterface;

class TemplateEnvironment
{

    public function __construct(TemplateLoaderInterface $loader, array $options = [])
    {
        $this->loader = $loader;
        $this->options = $options;
    }

    /**
     * Undocumented function
     *
     * @param string $template
     * @param array $context
     * @return void
     */
    public function view(string $template, array $context = [])
    {
        return (new Template($this->options))->view($template, $context);
    }

    /**
     * Undocumented function
     *
     * @param object $extension
     * @return void
     */
    public function addExtension(object $extension): void
    {
        if ($extension)
            $this->extension = $extension;
    }
    
}