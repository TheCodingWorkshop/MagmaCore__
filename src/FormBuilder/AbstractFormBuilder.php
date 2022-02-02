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

use MagmaCore\FormBuilder\Exception\FormBuilderInvalidArgumentException;

abstract class AbstractFormBuilder implements FormBuilderInterface
{

    /** @var array - constants which defines the various parts the form element */
    const FORM_PARTS = [
        'action' => '',
        'method' => 'post',
        'accept-charset' => '',
        'enctype' => 'application/x-www-form-urlencoded',
        'id' => '',
        'class' => ['uk-form-horizontal'],
        'rel' => '',
        'target' => '_self', /* defaults loads into itself */
        'novalidate' => false,
        "autocomplete" => false,
        "leave_form_open" => false,
        "data-turbo-frame" => ''
        //"onSubmit" => "UIkitNotify()"
    ];

    /** class constants for allowable field/input types */
    const SUPPORT_INPUT_TYPES = [
        'textarea',
        'select',
        'checkbox',
        'multiple_checkbox',
        'radio',
        'text',
        'range',
        'number',
        'datetime-local',
        'time',
        'date',
        'input',
        'password',
        'email',
        'color',
        'button',
        'reset',
        'submit',
        'tel',
        'search',
        'url',
        'file',
        'month',
        'week',
        'hidden',
        'editor',
    ];
    /** @var array */
    const HTML_ELEMENT_PARTS = [
        'before' => '',
        'after' => '',
        'element' => 'div',
        'element_class' => ['uk-form-controls'],
        'element_id' => '',
        'element_style' => ''
    ];

    /** @var array */
    protected array $inputs = [];
    /** @var array */
    protected array $formAttr = [];

    /**
     * Main class constructor
     */
    public function __construct()
    { }

    /**
     * @param string $key
     * @param $value
     * @return bool
     */
    protected function setAttributes(string $key, $value): bool
    {
        if (empty($key)) {
            throw new FormBuilderInvalidArgumentException('Invalid or empty attribute key. Ensure the key is present and valid');
        }
        switch ($key):
            case 'action':
            case 'data-turbo-frame' :
                if (!is_string($key)) {
                    throw new FormBuilderInvalidArgumentException('Invalid action key. This must be a string.');
                }
                break;
            case 'method':
                if (!in_array($value, ['post', 'get', 'dialog'])) {
                    throw new FormBuilderInvalidArgumentException('Invalid form method. Either this is not set or you\'ve set an unsupported method type.');
                }
                break;
            case 'target' :
                if (!in_array($value, ['_self', '_blank', '_parent', '_top'])) {
                    throw new FormBuilderInvalidArgumentException('Invalid key');
                }
                break;
            case 'enctype':
                if (!in_array($value, ['application/x-www-form-urlencoded', 'multipart/form-data', 'text/plain'])) {
                    throw new FormBuilderInvalidArgumentException();
                }
                break;
            case 'id':
            case 'class':
                break;
            case 'novalidate':
            case 'autocomplete' :
                if (!is_bool($value)) {
                    throw new FormBuilderInvalidArgumentException();
                }
                break;
            default:
                return false;
                break;
        endswitch;

        $this->formAttr[$key] = $value;

        return true;
    }

    /**
     * Set the form input attributes if any attribute if left empty
     * then it will use the default if any is set
     *
     * @param string $key
     * @param $value
     * @return bool
     */
    public function set(string $key, $value): bool
    {
        if (empty($key)) {
            throw new FormBuilderInvalidArgumentException('Invalid or empty attribute key. Ensure the key is present and valid');
        }
        switch ($key):
            case 'type':
                if (!in_array($value, self::SUPPORT_INPUT_TYPES)) {
                    throw new FormBuilderInvalidArgumentException('Unsupported object type ' . $value);
                }
                break;
            default:
                return false;
                break;
        endswitch;

        $this->inputs[$key] = $value;

        return true;
    }

}