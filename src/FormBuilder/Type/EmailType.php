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

namespace MagmaCore\FormBuilder\Type;

use MagmaCore\FormBuilder\FormExtensionTypeInterface;
use MagmaCore\Utility\Yaml;

class EmailType extends InputType implements FormExtensionTypeInterface
{

    /** @var string - this is the text type extension */
    protected string $type = 'email';
    /** @var array - returns the defaults for the input type */
    protected array $defaults = [];

    /**
     * @inheritdoc
     *
     * @param array $fields
     * @param mixed|null $options
     * @param array $settings
     */
    public function __construct(array $fields, mixed $options = null, array $settings = [])
    {
        /* Assigned arguments to parent InputType constructor */
        parent::__construct($fields, $options, $settings);
    }

    /**
     * @inheritdoc
     *
     * @param array $extensionOptions
     * @return void
     */
    public function configureOptions(array $extensionOptions = []): void
    {
        $this->defaults = [
            /**
             * An <input> element with type="email" that must be in the following 
             * order: characters@characters.domain (characters followed by an @ sign, 
             * followed by more characters, and then a "."
             * After the "." sign, add at least 2 letters from a to z:
             */
            'pattern' => Yaml::file('app')['security']['email_pattern'],
            'list' => '',
            'maxlength' => '',
            'minlength' => '',
            'multiple' => false, /* whether or not to allow multiple email separated by comma (,) */
            'placeholder' => '',
            'readonly' => false,
            'size' => '',
            'value' => ''
        ];

        parent::configureOptions($this->defaults);
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getExtensionDefaults() : array
    {
        return $this->defaults;
    }

    /**
     * Publicize the default object options to the base class
     *
     * @return array
     */
    public function getOptions() : array
    {
        return parent::getOptions();
    }

    /**
     * Return the third argument from the add() method. This array can be used
     * to modify and filter the final output of the input and HTML wrapper
     *
     * @return array
     */
    public function getSettings() : array
    {
        return parent::getSettings();
    }


}