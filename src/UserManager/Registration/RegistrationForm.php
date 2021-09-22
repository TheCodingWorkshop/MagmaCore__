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

namespace MagmaCore\UserManager\Registration;

use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;
use Exception;

class RegistrationForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

    /** @var FormBuilderBlueprintInterface $blueprint */
    private FormBuilderBlueprintInterface $blueprint;

    /**
     * Main class constructor
     *
     * @param FormBuilderBlueprint $blueprint
     * @return void
     */
    public function __construct(FormBuilderBlueprint $blueprint)
    {
        $this->blueprint = $blueprint;
        parent::__construct();
    }

    /**
     * Construct the security login form. The attribute name='{string}' must match
     * the string name pass to the $this->form->isSubmittable() method within the
     * any method checking if the form canHandleRequest and isSubmittable
     *
     * @param string $action
     * @param object|null $dataRepository
     * @param object|null $callingController
     * @return string
     * @throws Exception
     */
    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null): string
    {
        return $this->form(['action' => $action, 'class' => 'uk-form-horizontal'])
            ->addRepository($dataRepository)
            ->add($this->blueprint->text('firstname'))
            ->add($this->blueprint->text('lastname'))
            ->add($this->blueprint->email('email', ['uk-width-1-2'], null, true))
            ->add($this->blueprint->password(
                'client_password_hash',
                [],
                null,
                'new-password',
                true),
                NULL,
                $this->blueprint->settings(false, null, true, 'Password')
            )
            ->add(
                $this->blueprint->submit(
                    'register-registration',
                    ['uk-button', 'uk-button-primary'],
                    'Register new account'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true, 'Remember Me')
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}
