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

namespace MagmaCore\ThemeBuilder;

abstract class AbstractThemeBuilder implements ThemeBuilderInterface
{

    protected ?object $cssDriver = null;
    protected array $cssOptions = [];

    /**
     * @param object|null $cssDriver
     * @param array $cssOptions
     */
    public function __construct(object $cssDriver = null)
    {
        $this->cssDriver = $cssDriver;
    }

    public function getCssDriver(): object
    {
        return $this->cssDriver;
    }

}