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
use MagmaCore\FormBuilder\Type\EmailType;
use MagmaCore\FormBuilder\Type\RadioType;
use MagmaCore\FormBuilder\Type\HiddenType;
use MagmaCore\FormBuilder\Type\SelectType;
use MagmaCore\FormBuilder\Type\SubmitType;
use MagmaCore\FormBuilder\Type\CheckboxType;
use MagmaCore\FormBuilder\Type\PasswordType;
use MagmaCore\FormBuilder\Type\TextareaType;
use MagmaCore\FormBuilder\Type\MultipleCheckboxType;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;

class FormBuilderBlueprint implements FormBuilderBlueprintInterface
{

    private function args(
        string $name,
        array $class = [],
        mixed $value = null,
        string|null $placeholder = null
    ): array {
        return [
            'name' => $name,
            'class' => array_merge(['uk-input'], $class),
            'placeholder' => ($placeholder !== null) ? $placeholder : '',
            'value' => ($value !== null) ? $value : ''

        ];
    }

    private function arg(
        string $name,
        array $class = [],
        mixed $value = null
    ): array {
        return [
            'name' => $name,
            'class' => $class,
            'value' => ($value !== null) ? $value : ''

        ];
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @param array $class
     * @param mixed $value
     * @param string|null $placeholder
     * @return array
     */
    public function text(
        string $name,
        array $class = [],
        mixed $value = null,
        bool $disabled = false,
        string|null $placeholder = null
    ): array {
        return [
            TextType::class => [
                array_merge(
                    $this->args($name, $class, $value, $placeholder),
                    ['disabled' => $disabled]
                )
            ]
        ];

    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @param mixed $value
     * @param array $class
     * @return array
     */
    public function hidden(
        string $name,
        mixed $value = null,
        array $class = []
    ): array {
        return [
            HiddenType::class => [
                array_merge(
                    $this->arg($name, $class, $value),
                    []
                )

            ]
        ];

    }

    public function textarea(
        string $name,
        array $class = [],
        mixed $id = null,
        string|null $placeholder = null,
        int $rows = 5,
        int $cols = 33,
    ): array {
        return [
            TextareaType::class => [
                'name' => $name,
                'class' => $class,
                'id' => $id,
                'placeholder' => $placeholder,
                'rows' => $rows,
                'cols' => $cols
            ]
        ];

    }


    public function email(
        string $name,
        array $class = [],
        mixed $value = null,
        bool $required = true,
        bool $pattern = false,
        string|null $placeholder = null
    ): array {
        return [
            EmailType::class => [
                array_merge(
                    $this->args($name, $class, $value, $placeholder),
                    ['required' => $required, 'pattern' => $pattern]
                )
            ]
        ];
    }

    public function password(
        string $name,
        array $class = [],
        mixed $value = null,
        string|null $autocomplete = null,
        bool $required = false,
        bool $pattern = false,
        bool $disabled = false,
        string|null $placeholder = null
    ): array {
        return [
            PasswordType::class => [
                array_merge(
                    $this->args($name, $class, $value, $placeholder),
                    ['autocomplete' => $autocomplete, 'required' => $required, 'pattern' => $pattern, 'disabled' => $disabled]
                )
            ]
        ];
    }
    
    /**
     * Undocumented function
     *
     * @param string $name
     * @param array $class
     * @param mixed $value = null
     * @return void
     */
    public function radio(string $name, array $class = [], mixed $value = null): array
    {
        return [
            RadioType::class => [
                array_merge(
                    $this->arg($name, array_merge(['uk-radio'], $class), $value),
                    []
                )
            ]
        ];
    }

    public function checkbox(
        string $name,
        array $class = [],
        mixed $value = null
    ): array {
        return [
            CheckboxType::class => [
                $this->arg($name, array_merge(['uk-checkbox'], $class), $value)
            ]
        ];
    }

    public function select(
        string $name,
        array $class = [],
        string $id = null,
        mixed $size = null,
        bool $multiple = false,
    ): array
    {

        return [
            SelectType::class => [
                'name' => $name,
                'class' => $class,
                'id' => $id,
                'size' => $size,
                'multiple' => $multiple
            ]
        ];
    }


    /**
     * Undocumented function
     *
     * @param string $name
     * @param array $class
     * @param mixed $value
     * @return array
     */
    public function multipleCheckbox(
        string $name,
        array $class = [],
        mixed $value = null
    ): array {
        return [
            MultipleCheckboxType::class => [
                $this->arg($name, array_merge(['uk-checkbox'], $class), $value)
            ]
        ];
    }



    public function submit(
        string $name,
        array $class = [],
        mixed $value = null
    ): array {
        return [
            SubmitType::class => [
                $this->arg($name, $class, $value)
            ]
        ];
    }

    /**
     * Undocumented function
     *
     * @param array $choices
     * @return array
     */
    public function choices(array $choices, mixed $default = null): array
    {
        return [
            'choices' => $choices,
            'default' => ($default !==null) ? $default : 'pending'
        ];
    }

    /**
     * Undocumented function
     *
     * @param boolean $inlineFlipIcon
     * @param string $inlineIcon
     * @param boolean $showLabel
     * @param string $newLabel
     * @param boolean $wrapper
     * @return array
     */
    public function settings(
        bool $inlineFlipIcon = false,
        ?string $inlineIcon = null,
        bool $showLabel = true,
        ?string $newLabel = null,
        bool $wrapper = false,
        ?string $checkboxLabel = null,
        ?string $description = null): array
    {
        return [
            'inline_flip_icon' => $inlineFlipIcon,
            'inline_icon' => $inlineIcon,
            'show_label' => $showLabel,
            'new_label' => $newLabel,
            'before_after_wrapper' => $wrapper,
            'checkbox_label' => $checkboxLabel,
            'description' => $description
        ];
    }
}
