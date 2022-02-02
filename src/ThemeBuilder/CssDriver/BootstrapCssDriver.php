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

namespace MagmaCore\ThemeBuilder\CssDriver;

use MagmaCore\Base\Traits\BaseReflectionTrait;
use MagmaCore\ThemeBuilder\Contracts\AlertInterface;
use MagmaCore\ThemeBuilder\Contracts\ButtonInterface;
use MagmaCore\ThemeBuilder\Contracts\DropdownInterface;
use MagmaCore\ThemeBuilder\Contracts\IconNavInterface;
use MagmaCore\ThemeBuilder\Contracts\PaginationInterface;
use MagmaCore\ThemeBuilder\Contracts\TabInterface;
use MagmaCore\ThemeBuilder\Contracts\TableInterface;
use MagmaCore\ThemeBuilder\Contracts\TextInterface;
use MagmaCore\ThemeBuilder\Contracts\TooltipInterface;
use MagmaCore\ThemeBuilder\ThemeBuilder;

class BootstrapCssDriver extends ThemeBuilder implements TableInterface

//    TextInterface,
//    AlertInterface,
//    ButtonInterface,
//    DropdownInterface,
//    IconNavInterface,
//    PaginationInterface,
//    TooltipInterface
{

    use BaseReflectionTrait;

    protected array $cssOptions = [];
    protected string $cssDriver = 'bootstrap';

    public function __construct(array $cssOptions = [])
    {
        $this->cssOptions = $cssOptions;
    }

    /**
     * Return the current driver string
     * @return string
     */
    public function driver(): string
    {
        return $this->cssDriver;
    }

    public function theme(?string $key = null): array
    {
        if (is_array($this->table())) {
            return $this->table()[$key];
        }
    }

    private function table(): array
    {
        return [
            'table_component' => [
                'table' => '',
                'divider' => '',
                'striped' => '',
                'hover' => '',
                'small' => '',
                'large' => '',
                'justify' => '',
                'middle' => '',
                'responsive' => '',
                'shrink' => '',
                'expand' => '',
                'link' => ''
            ]

        ];
    }

}