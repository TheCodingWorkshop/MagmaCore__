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

namespace MagmaCore\FormBuilder;

use MagmaCore\FormBuilder\Type\TextType;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;

class FormBuilderBlueprint implements FormBuilderBlueprintInterface
{

    public function input(string $type, string $name, mixed $value = '', array $class = [], string|null $placeholder = null)
    {
        return [
            TextType::class => [
                'name' => $name, 'type' => $type, 'value' => $value, 'placeholder' => $placeholder, 'class' => array_merge($class, ['uk-input'])
            ]
        ];
    }
    public function textarea()
    {

    }
}