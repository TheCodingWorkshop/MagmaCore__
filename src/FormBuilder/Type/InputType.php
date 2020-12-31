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

use MagmaCore\FormBuilder\Exception\FormBuilderInvalidArgumentException;
use MagmaCore\FormBuilder\FormExtensionTypeInterface;
use MagmaCore\FormBuilder\FormBuilderTypeInterface;
use MagmaCore\FormBuilder\FormBuilderTrait;


class InputType implements FormBuilderTypeInterface
{

    use FormBuilderTrait;

    /** @var string - returns the name of the extension. IMPORTANT */
    protected string $type = '';
     /** @var array - returns the combined attr options from extensions and constructor fields */
    protected array $attr = [];
    /** @var array - return an array of form fields attributes */
    protected array $fields = [];
    /** @var array returns an array of form settings */
    protected array $settings = [];
    /** @var mixed */
    protected $options = null;
    /** @var array returns an array of default options set */
    protected array $baseOptions = [];

    /**
     * Main class constructor
     *
     * @param array $fields
     * @param mixed $options
     * @param array $settings
     */
    public function __construct(array $fields, $options = null, array $settings = [])
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
            'type' => $this->type, 
            'name' => '', 
            'id' => $this->fields['name'], 
            'class' => ['uk-input'],
            'checked' => false, 
            'required' => false, 
            'disabled' => false, 
            'autofocus' => false,
            'autocomplete' => false
        ];
    }

    /**
     * Construct the name of the extension type using the upper camel case
     * naming convention. Extension type name i.e Text will also be suffix
     * with the string (Type) so becomes TextType
     *
     * @return string
     */
    private function buildExtensionName() : string
    {
        $extensionName = lcfirst(str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', $this->type . 'Type'))));
        $extensionName = ucwords($extensionName);
        return $extensionName;
    }

    /**
     * Construct the extension namespace string. Extension name is captured from
     * the buildExtensionName() method name. Extension objects are also instantiated
     * from this method and check to ensure its implementing the correct interface
     * else will throw an invalid argument exception.
     *
     * @return void
     */
    private function buildExtensionObject() : void
    {
        $getExtensionNamespace = 'MagmaCore\FormBuilder\Type\\' . $this->buildExtensionName();
        $getExtensionObject = new $getExtensionNamespace($this->fields);
        if (!$getExtensionObject instanceof FormExtensionTypeInterface) {
            throw new FormBuilderInvalidArgumentException($this->buildExtensionName() . ' is not a valid form extension type object.');
        }

    }

    /**
     * @inheritdoc
     *
     * @param array $extensionOptions
     * @return void
     */
    public function configureOptions(array $extensionOptions = []) : void
    {
        if (empty($this->type)) {
            throw new FormBuilderInvalidArgumentException('Sorry please set the ' . $this->type . ' property in your extension class.');
        }

        if (!$this->buildExtensionObject()) {
            $defaultWithExtensionOptions = (!empty($extensionOptions) ? array_merge($this->getBaseOptions(), $extensionOptions) : $this->getBaseOptions());
            if ($this->fields) { /* field options set from the constructor */
                $this->throwExceptionOnBadInvalidKeys(
                    $this->fields, 
                    $defaultWithExtensionOptions, 
                    $this->buildExtensionName()
                );

                /* Phew!! */
                /* Lets merge the options from the our extension with the fields options */
                /* assigned complete merge to $this->attr class property */
                $this->attr = array_merge($defaultWithExtensionOptions, $this->fields);
            }
        }
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getOptions() : array
    {
        return $this->attr;
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getSettings() : array
    {
        $defaults = [
            'before_after_wrapper' => true,
            'container' => true,
            'show_label' => true,
            'new_label' => '',
            'inline_icon' => '',
            'inline_icon_class' => '',
            'inline_flip_icon' => false
        ];
        return (!empty($this->settings) ? array_merge($defaults, $this->settings) : $defaults);
    }

    public function filtering()
    {
        return $this->renderHtmlElement($this->attr, $this->options);
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function view() : string
    {
        switch ($this->getType()) :
            case 'radio' :
                return sprintf("%s", $this->filtering());
                break;
            case 'checkbox' :
                return sprintf("\n<input %s>&nbsp;%s\n", $this->filtering(), ($this->settings['checkbox_label'] !='' ? $this->settings['checkbox_label'] : ''));
                break;
            default :
                return sprintf("\n<input %s>\n", $this->filtering());
                break;
        endswitch;
    }

}