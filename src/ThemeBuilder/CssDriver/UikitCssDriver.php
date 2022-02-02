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

use MagmaCore\Base\Exception\BaseException;
use MagmaCore\Base\Traits\BaseReflectionTrait;
use MagmaCore\ThemeBuilder\Contracts\ContainerInterface;
use MagmaCore\ThemeBuilder\Contracts\AlertInterface;
use MagmaCore\ThemeBuilder\Contracts\ButtonInterface;
use MagmaCore\ThemeBuilder\Contracts\DropdownInterface;
use MagmaCore\ThemeBuilder\Contracts\FlexInterface;
use MagmaCore\ThemeBuilder\Contracts\FormInterface;
use MagmaCore\ThemeBuilder\Contracts\IconNavInterface;
use MagmaCore\ThemeBuilder\Contracts\MarginInterface;
use MagmaCore\ThemeBuilder\Contracts\PaddingInterface;
use MagmaCore\ThemeBuilder\Contracts\PaginationInterface;
use MagmaCore\ThemeBuilder\Contracts\TabInterface;
use MagmaCore\ThemeBuilder\Contracts\TableInterface;
use MagmaCore\ThemeBuilder\Contracts\TextInterface;
use MagmaCore\ThemeBuilder\Contracts\TooltipInterface;
use MagmaCore\ThemeBuilder\Contracts\UtilityInterface;
use MagmaCore\ThemeBuilder\Contracts\WidthInterface;
use MagmaCore\ThemeBuilder\ThemeBuilder;

/**
 * UikitCssDriver class should only implement the interface it supports. Each interface builds
 * 1 css component ie. table, tab, pagination etc...
 * All concrete implementation is done within this class or can be done from a trait, which we must
 * then import within this class
 */
class UikitCssDriver extends ThemeBuilder implements TableInterface,
                                                        PaginationInterface,
                                                        UtilityInterface,
                                                        ContainerInterface,
                                                        MarginInterface,
                                                        PaddingInterface,
                                                        FormInterface,
                                                        WidthInterface,
                                                        AlertInterface,
                                                        FlexInterface,
                                                        TextInterface
{

    use BaseReflectionTrait;

    /* @var array */
    protected array $cssOptions = [];
    /* @var string */
    protected string $driver = 'uikit';

    /**
     * Access any pass in css options
     * @param array $cssOptions
     */
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
        return $this->driver;
    }

    /**
     * @param string|null $key
     * @return array
     * @throws \ReflectionExceptions
     */
    public function theme(array $elements = []): self
    {

//        $reflection = $this->reflection(get_class($this));
//        $methods = $reflection->methods();
//        var_dump($methods);
//        die;
//        if (is_array($this->table())) {
//            return $this->table()[$key];
//        }

        return $this;
    }

    /**
     * Concrete implementation for the table interface. We are simple returning an array
     * of css elements which builds the table within the supported css framework
     *
     * @return \string[][]
     */
    public function table(): array
    {
        return [
            'table' => 'uk-table',
            'divider' => 'uk-table-divider',
            'striped' => 'uk-table-striped',
            'hover' => 'uk-table-hover',
            'small' => 'uk-table-small',
            'large' => 'uk-table-large',
            'justify' => 'uk-table-justify',
            'middle' => 'uk-table-middle',
            'responsive' => 'uk-table-responsive',
            'shrink' => 'uk-table-shrink',
            'expand' => 'uk-table-expand',
            'link' => 'uk-table-link'

        ];
    }

    public function pagination(): array
    {
        return [
            'pagination' => 'uk-pagination',
            'previous' => 'uk-pagination-previous',
            'next' => 'uk-pagination-next'
        ];
    }

    public function margin(): array
    {
        return [
            'margin' => 'uk-margin',
            'small' => 'uk-margin-small',
            'small-top' => 'uk-margin-small-top',
            'small-bottom' => 'uk-marhin-small-bottom',
            'small-right' => 'uk-margin-small-right',
            'small-left' => 'uk-margin-small-left',
            'medium' => 'uk-margin-medium',
            'medium-top' => 'uk-margin-mnedium-top',
            'medium-bottom' => 'uk-margin-medium-bottom',
            'medium-left' => 'uk-margin-medium-left',
            'medium-right' => 'uk-margin-medium-right',
            'large' => 'uk-margin-large',
            'large-top' => 'uk-margin-large-top',
            'large-bottom' => 'uk-margin-large-bottom',
            'large-left' => 'uk-margin-large-left',
            'large-right' => 'uk-margin-large-right',
            'xlarge-margin' => 'uk-margin-xlarge',
            'xlarge-top' => 'uk-margin-xlarge-top',
            'xlarge-bottom' => 'uk-margin-xlarge-bottom',
            'xlarge-left' => 'uk-margin-xlarge-left',
            'xlarge-right' => 'uk-margin-xlarge-right',
            'remove' => 'uk-margin-remove',
            'top' => 'uk-margin-top',
            'bottom' => 'uk-margin-bottom',
            'right' => 'uk-margin-right',
            'left' => 'uk-margin-left',
            'remove-top' => 'uk-margin-remove-top',
            'remove-bottom' => 'uk-margin-remove-bottom',
            'remove-left' => 'uk-margin-remove-left',
            'remove-right' => 'uk-margin-remove-right',
            'remove-vertical' => 'uk-margin-remove-adjacent',
            'remove-first-child' => 'uk-margin-remove-first-child',
            'remove-last-child' => 'uk-margin-remove-last-child',
            'auto' => 'uk-margin-auto',
            'auto-top' => 'uk-margin-auto-top',
            'auto-bottom' => 'uk-margin-auto-bottom',
            'auto-left' => 'uk-margin-auto-left',
            'auto-right' => 'uk-margin-auto-right',
            'auto-vertical' => 'uk-margin-auto-vertical'
        ];

    }

    public function padding(): array
    {
        return [
            'padding' => 'uk-padding',
            'small' => 'uk-padding-small',
            'large' => 'uk-padding-smsll',
            'remove' => 'uk-padding-remove',
            'remove-top' => 'uk-padding-remove-top',
            'remove-bottom' => 'uk-padding-remove-bottom',
            'remove-left' => 'uk-padding-remove-left',
            'remove-right' => 'uk-padding-remove-right',
            'remove-vertical' => 'uk-padding-remove-vertical',
            'remove-horizonatal' => 'uk-padding-remove-horizontal'
        ];
    }

    public function utility(): array
    {
        return [
            'inline' => 'uk-inline',
            'overflow' => ['hidden' => 'uk-overflow-hidden', 'auto' => 'uk-overflow-auto'],
            'float' => [
                'left' => 'uk-float-left',
                'right' => 'uk-float-right',
                'clearfix' => 'uk-clearfix'
            ]
        ];
    }

    public function container(): array
    {
        return [
            'container' => 'uk-container',
            'container-small' => 'uk-container-small',
            'large' => 'uk-container-large',
            'xlarge' => 'uk-container-xlarge',
            'expand' => 'uk-container-expand'
        ];
    }

    public function form(): array
    {
        return [
            'input' => 'uk-input',
            'select' => 'uk-select',
            'checkbox' => 'uk-checkbox',
            'radio' => 'uk-radio',
            'range' => 'uk-range',
            'textarea' => 'uk-textarea',
            'fieldset' => 'uk-fieldset',
            'legend' => 'uk-legend',
            'form-large' => 'uk-form-large',
            'form-small' => 'uk-form-small',
            'form-width-medium' => 'uk-form-width-medium',
            'form-width-small' => 'uk-form-width-small',
            'form-width-xsmall' => 'uk-form-width-xsmall',
            'form-width-large' => 'uk-form-width-large',
            'form-blank' => 'uk-form-blank',
            'stacked' => 'uk-form-stacked',
            'horizontal' => 'uk-form-horizontal',
            'label' => 'uk-form-label',
            'controls' => 'uk-form-controls',
            'controls-text' => 'uk-form-controls-text',
            'form-icon' => 'uk-form-icon',
            'form-icon-flip' => 'uk-form-icon-flip',
        ];
    }

    public function alert(): array
    {
        return [
            'alert' => 'uk-alert',
            'close' => 'uk-alert-close',
            'primary' => 'uk-alert-primary',
            'success' => 'uk-alert-success',
            'warning' => 'uk-alert-warning',
            'danger' => 'uk-alert-danger'
        ];
    }

    public function width(): array
    {

    }

    public function flex(): array
    {

    }

    public function text(): array
    {
        return [
            'lead' => 'uk-text-lead',
            'meta' => 'uk-text-meta',
            'small' => 'uk-text-small',
            'large' => 'uk-text-large',
            'default' => 'uk-text-default',
            'light' => 'uk-text-light',
            'lighter' => 'uk-text-lighter',
            'normal' => 'uk-text-normal',
            'bold' => 'uk-text-bold',
            'bolder' => 'uk-text-bolder',
            'italic' => 'uk-text-italic',
            'capitalize' => 'uk-text-capitalize',
            'uppercase' => 'uk-text-uppercase',
            'lowercase' => 'uk-text-lowercase',
            'decoration-none' => 'uk-text-decoration-none',
            'muted' => 'uk-text-muted',
            'emphasis' => 'uk-text-emphasis',
            'primary' => 'uk-text-primary',
            'secondary' => 'uk-text-secondary',
            'success' => 'uk-text-success',
            'warning' => 'uk-text-warning',
            'danger' => 'uk-text-danger',
            'left' => 'uk-text-left',
            'right' => 'uk-text-right',
            'center' => 'uk-text-center',
            'justify' => 'uk-text-justify',
            'top' => 'uk-text-top',
            'bottom' => 'uk-text-bottom',
            'middle' => 'uk-text-middle',
            'baseline' => 'uk-text-baseline',
            'truncate' => 'uk-text-truncate',
            'break' => 'uk-text-break',
            'nowrap' => 'uk-text-nowrap',
            'background' => 'uk-text-background'
        ];
    }

}