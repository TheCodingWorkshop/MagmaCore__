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

namespace MagmaCore\Themes\Library\Uikit;

use MagmaCore\Themes\Library\AbstractThemeLibrary;

class Uikit extends AbstractThemeLibrary
{

    public function theme(?string $wildcard = null): array
    {
        return [
            'form' => [
                'input' => 'uk-input',
                'checkbox' => 'uk-checkbox',
                'radio' => 'uk-radio',
                'textarea' => 'uk-textarea',
                'select' => 'uk-select',
                'range' => 'uk-range',
                'fieldset' => 'uk-fieldset',
                'legend' => 'uk-legend',    
            ],
            'state_modifiers' => [
                'form-danger' => 'uk-form-danger',
                'form-success' => 'uk-form-success',
            ],
            'size_modifiers' => [
                'form-large' => 'uk-form-large',
                'form-small' => 'uk-form-small',
                'form-width-medium' => 'uk-form-width-medium',
                'form-width-xsmall' => 'uk-form-width-xsmall',
                'width-' . $wildcard => 'uk-form-width-' . $wildcard
            ]
        ];
    
    }

}
