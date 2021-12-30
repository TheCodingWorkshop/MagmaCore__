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

interface FormBuilderBlueprintInterface
{

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
    ): array;

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
    ): array;

    public function textarea(
        string $name,
        array $class = [],
        mixed $id = null,
        string|null $placeholder = null,
        int $rows = 5,
        int $cols = 33,
    ): array;

    public function email(
        string $name,
        array $class = [],
        mixed $value = null,
        bool $required = true,
        bool $pattern = false,
        string|null $placeholder = null
    ): array;

    public function password(
        string $name,
        array $class = [],
        mixed $value = null,
        string|null $autocomplete = null,
        bool $required = false,
        bool $pattern = false,
        bool $disabled = false,
        string|null $placeholder = null
    ): array;

    /**
     * Undocumented function
     *
     * @param string $name
     * @param array $class
     * @return void
     */
    public function radio(string $name, array $class = [], mixed $value = null): array;

    public function submit(
        string $name,
        array $class = [],
        mixed $value = null
    ): array;

    public function checkbox(
        string $name,
        array $class = [],
        mixed $value = null
    ): array;

    public function select(
        string $name,
        array $class = [],
        string $id = null,
        mixed $value = null,
        bool $multiple = false,

    ): array;

    public function multipleCheckbox(
        string $name,
        array $class = [],
        mixed $value = null
    ): array;

    /**
     * Undocumented function
     *
     * @param array $choices
     * @return array
     */
    public function choices(array $choices, string|int $default = null, object $form = null): array;

    /**
     * Undocumented function
     *
     * @param boolean $inlineIcon
     * @param string $icon
     * @param boolean $showLabel
     * @param string $newLabel
     * @param boolean $wrapper
     * @return array
     */
    public function settings(
        bool $inlineIcon = false,
        string $icon = null,
        bool $showLabel = true,
        string $newLabel = null,
        bool $wrapper = false,
        string|null $checkboxLabel = null,
        string|null $description = null
    ): array;
}
