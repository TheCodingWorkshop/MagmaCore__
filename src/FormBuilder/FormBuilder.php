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

use Exception;
use MagmaCore\Error\Error;
use MagmaCore\Http\Request;
use MagmaCore\Session\SessionTrait;
use MagmaCore\FormBuilder\Traits\FormalizerTrait;
use MagmaCore\FormBuilder\Exception\FormBuilderInvalidArgumentException;
use MagmaCore\FormBuilder\Traits\FormTrait;

class FormBuilder extends AbstractFormBuilder
{

    use FormBuilderTrait,
        FormalizerTrait,
        SessionTrait,
        FormTrait;

    protected array $inputObject = [];
    protected array $htmlAttr = [];
    protected string $html = '';
    protected bool $addCsrf = true;
    protected string $element = '';
    protected Object $error;
    private object|null $dataRepository = null;

    /**
     * Main class constructor
     * 
     * @return void
     */
    public function __construct(Error $error)
    {
        parent::__construct();
        $this->error = $error;
    }

    /**
     * Returns the request/response object
     *
     * @param string $url
     * @return Request
     */
    public function getRequest(): Request
    {
        return new Request();
    }

    /**
     * Use to customzied the HTML form attributes
     *
     * @param array $args
     * @return FormBuilder
     */
    public function form(array $args = []) : self
    {
        if ($args) {
            $this->formAttr = array_merge(self::FORM_PARTS, $args);
        } else {
            $this->formAttr = self::FORM_PARTS;
        }
        if (is_array($this->formAttr)) {
            foreach ($this->formAttr as $key => $value) {
                if (!$this->setAttributes($key, $value)) {
                    $this->setAttributes($key, self::FORM_PARTS[$key]);
                }
            }
        }

        return $this;
    }

    /**
     * This method allows us to chain multiple input types together to build the required
     * form structure
     *
     * @param array $args - optional argument to modified the values of the input wrapping tag
     * @param null $options
     * @return mixed
     */
    public function add(array $args = [], $options = null, array $settings = []) : self
    { 
        if (is_array($args)) {
            foreach ($args as $objectType => $objectTypeOptions) {
                $newTypeObject = new $objectType($objectTypeOptions, $options, $settings);
                if (!$newTypeObject instanceof FormBuilderTypeInterface) {
                    throw new FormBuilderInvalidArgumentException('"' . $objectType . '" is not a valid form type object.', 0);
                }
                $this->inputObject[] = $newTypeObject;
                return $this;
            }
        }
    }

    /**
     * This methods get chain at the very end after each add() method. And will attempt to build
     * the required input based on each add() method arguments. Theres an option to have
     * HTML elements wrap around each input tag for better styling of each element
     *
     * @param array $args
     * @param bool $noForm = true Whether to render the actual form tags
     * @return string|bool
     * @throws Exception
     */
    public function build(array $args = [], bool $noForm = true, bool $disableCsrf = true): string|bool
    { 
        if ($args) {
            $this->htmlAttr = array_merge(self::HTML_ELEMENT_PARTS, $args);
        } else {
            $this->htmlAttr = self::HTML_ELEMENT_PARTS;
        }
        if ($noForm) {
            $this->element .= sprintf('<form %s>', $this->renderHtmlElement($this->formAttr));
        }
            if (is_array($this->inputObject)) {

                foreach ($this->inputObject as $objectType) {
                    foreach ((array)$objectType->configureOptions() as $key => $value) {
                        $this->set($key, $value);
                    }
                    $this->element .= $this->processFormFields($objectType);
                }
            }
            if ($disableCsrf === true) {
                $this->element .= $this->csrfForm($this->formAttr['action']);
            }
            $this->element .= PHP_EOL;
        if ($noForm) {
            $this->element .= '</form>';
        }

        $this->element .= PHP_EOL;
        if (isset($this->element) && !empty($this->element)) {
            return $this->element;
        }
        return false;
    }

    /**
     * Build the form HTML element
     *
     * @param Object $objectType
     * @return string
     */
    private function processFormFields(Object $objectType) : string
    {
        $html = '';
        foreach (self::SUPPORT_INPUT_TYPES as $field) :    
            switch ($objectType->getType()) :
                case $field :
                
                    // [inline_flip_icon, inline_icon, inline_icon_class, before_after_wrapper etc...]
                    extract ($objectType->getSettings(), EXTR_SKIP);
                    /* Set the container icon flip left or right */
                    $flip_icon = (isset($inline_flip_icon) && $inline_flip_icon == true) ? ' uk-form-icon-flip' : '';

                    /* Wrap the element and form input within a container element */
                    if ($container) {
                        
                        //[before, after, element, element_id, element_class, element_style]
                        extract ($this->htmlAttr);
                        if (!empty($element)) {
                    
                            /* Main wrapper element html tag are set with in the $before variable */
                            
                            $html .= (isset($before_after_wrapper) && $before_after_wrapper == true) ? "\n{$before}" : "";
                            
                            /* Form label wrapping element */
                            $html .= ($show_label === true) ? $this->formLabel($objectType->getOptions(), '', $new_label) : '';

                            if (!empty($description) && in_array('uk-form-stacked', $this->formAttr['class'])) {
                                $html .= "<div class=\"uk-text-meta uk-margin-small-bottom\">{$description}</div>";
                            }
                            $inline_icon_class = '';
                            /* If we are adding inline icon to the element lets add the class for it */
                            if (isset($inline_icon) && $inline_icon !=='') {
                                $html .= "\n" . '<div class="' . (isset($inline_icon_class) ? 'uk-inline' : $inline_icon_class) . '">' . "\n";

                                $html .= '<a class="uk-form-icon' . $flip_icon . '" href="#!" uk-icon="icon:' . (isset($inline_icon) ? $inline_icon : '') . '"></a>';

                            } else { /* if we are not using inline icon for our element */

                                $html .= "\n<{$element}"; // open
                                /* Get the element ID */
                                $html .= (!empty($element_id) ? ' id="' . $element_id . '"' : '');
                                /* Get the element class */
                                $html .= (is_array($element_class) && count($element_class) > 0) ? ' class="' . implode(' ', $element_class) . '"' : '';
                                /* Get element style if we are using */
                                $html .= (!empty($element_style) ? ' style="' . $element_style . '"' : '');
                                $html .= '>'; // close

                            }
                            $html .= $objectType->view();

                            /* Main element closing tag */
                            $html .= "</{$element}>\n";
                            /* container element wrapper */
                            $html .= (isset($before_after_wrapper) && $before_after_wrapper == true) ? "{$after}\n" : false;
 
                        }
                    } else { /* else we can render the form field outside of a container */
                        $html .= $objectType->view();
                    }

                    break;
            endswitch;
        endforeach;

        return $html;

    }

    private function throwExceptionIvalidWrapperClass(array $wrapperElement = null): void
    {
        if ($wrapperElement !==null) {
            foreach (['before', 'after'] as $wrapperClass) {
                if (!in_array($wrapperClass, array_keys($wrapperElement))) {
                    throw new \Exception(sprintf('Only before and after array keys are allowed here your key %s is not vali', implode(', ', $wrapperElement)));
                }
            }    
        }

    }


}