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
use MagmaCore\FormBuilder\Type\EmailType;
use MagmaCore\FormBuilder\Type\TextType;
use MagmaCore\FormBuilder\Type\SubmitType;

class RegistrationForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

	/**
	 * Construct the user registration form for the application front end
	 *
	 * @param string $action
	 * @param Object|null $Repository
	 * @return void
	 */
    public function createForm(string $action,?Object $Repository = null) 
	{
		$this->form(['action' => $action]) 
		->add([EmailType::class => ['name' => 'email']])
		->add([PasswordType::class => ['name' => 'password_hash']], null, ['new_label' => 'Password'])
		->build();
	} 
}
