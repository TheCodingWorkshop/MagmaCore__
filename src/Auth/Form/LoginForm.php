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

namespace MagmaCore\Auth\Form;

use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\FormBuilder\Type\PasswordType;
use MagmaCore\FormBuilder\Type\CheckboxType;
use MagmaCore\FormBuilder\Type\SubmitType;
use MagmaCore\FormBuilder\Type\EmailType;

class LoginForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

	/**
	 * Construct the security login form. The attribute name='{string}' must match 
	 * the string name pass to the $this->form->isSubmittable() method within the 
	 * any method checking if the form canHandleRequest and isSubmittable
	 *
	 * @param string $action
	 * @param Object|null $Repository
	 * @return void
	 */
    public function createForm(string $action,?Object $Repository = null) 
	{
		return $this->form(['action' => $action]) 
		->add([EmailType::class => ['name' => 'email']])
		->add([PasswordType::class => ['name' => 'password_hash']], null, ['new_label' => 'Password'])
		->add([CheckboxType::class => ['name' => 'remember_me', 'value' => false]], null, ['show_label' => false, 'checkbox_label' => 'Remember Me'])
		->add([SubmitType::class => ['name' => 'signin', 'value' => 'Login', 'class' => ['uk-button', 'uk-button-primary']]], null, ['show_label' => false])
		->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
	} 
}
