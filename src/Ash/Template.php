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

use MagmaCore\Ash\AbstractTemplate;

class Template extends AbstractTemplate
{

    /** @var array */
    protected array $templateEnvironment;

    /**
     * Undocumented function
     *
     * @param array $templateEnvironment
     * @return void
     */
    public function __construct(array $templateEnvironment)
    {
        parent::__construct($templateEnvironment);
    }

    /**
     * Undocumented function
     *
     * @param string $file
     * @param array $context
     * @return void
     */
    public function view(string $file, array $context = [])
    {
        $fileCache = $this->cache(TEMPLATES . $file);
        extract($context, EXTR_SKIP);
        require $fileCache;
    }


}
