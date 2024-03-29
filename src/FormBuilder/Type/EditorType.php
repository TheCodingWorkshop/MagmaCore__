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

use JetBrains\PhpStorm\Pure;
use MagmaCore\FormBuilder\FormBuilderTypeInterface;
use MagmaCore\FormBuilder\FormBuilderTrait;

class EditorType implements FormBuilderTypeInterface
{

    use FormBuilderTrait;

    /** @var string - returns the name of the extension. IMPORTANT */
    protected string $type = 'editor';
     /** @var array - returns the combined attr options from extensions and constructor fields */
    protected array $attr = [];
    /** @var array - return an array of form fields attributes */
    protected array $fields = [];
    /** @var array returns an array of form settings */
    protected array $settings = [];
    /** @var mixed */
    protected mixed $options = null;
    /** @var array returns an array of default options set */
    protected array $baseOptions = [];

    /**
     * @inheritdoc
     *
     * @param array $fields
     * @param mixed|null $options
     * @param array $settings
     */
    #[Pure] public function __construct(array $fields, mixed $options = null, array $settings = [])
    {
        $this->fields = $fields;
        $this->options = ($options !=null) ? $options : null;
        $this->settings = $settings;
        if (is_array($this->baseOptions)) {
            $this->baseOptions = $this->getBaseOptions();
        }
    }

    /**
     * Returns an array of base options.
     *
     * @return array
     */
    public function getBaseOptions() : array
    {
        return [
            'id' => '',
            'class' => ['summernote'],
        ];
    }


    /**
     * Options which are defined for this object type
     * Pass the default array to the parent::configureOptions to merge together
     *
     * @param array $options
     * @return void
     */
    public function configureOptions(array $options = []): void
    {
        $defaultWithExtensionOptions = (!empty($options) ? array_merge($this->baseOptions, $options) : $this->baseOptions);
        if ($this->fields) {
            $this->throwExceptionOnBadInvalidKeys(
                $this->fields, 
                $defaultWithExtensionOptions,
                __CLASS__
            );

            $this->attr = array_merge($defaultWithExtensionOptions, $this->fields);
        }
    }

    /**
     * Publicize the default object type to other classes
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Publicize the default object options to the base class
     *
     * @return array
     */
    public function getOptions() : array
    {
        return $this->attr;
    }

    /**
     * Return the third argument from the add() method. This array can be used
     * to modify and filter the final output of the input and HTML wrapper
     *
     * @return array
     */
    public function getSettings() : array
    {
        $defaults = [
            'container' => true,
            'show_label' => true,
            'new_label' => ''
        ];
        return (!empty($this->settings) ? array_merge($defaults, $this->settings) : $defaults);
    }

    /**
     * The pre filter method provides a way to filtered the build field input
     * on a a per object type basis as all types share the same basic tags
     *
     * there are cases where a tag is not required or valid within a
     * particular input/field. So we can filter it out here before being sent
     * back to the controller class
     * 
     * @return mixed - return the filtered or unfiltered string
     */
    #[Pure] public function filtering(): string
    {
        return  $this->renderHtmlElement($this->attr);
    }

    /**
     * Render the form view to the builder method within the base class
     *
     * @return string
     */
    public function view(): string
    { 
        return sprintf('<div %s>%s</div>', $this->filtering(), $this->options);
    }


}