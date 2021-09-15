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

namespace MagmaCore\UserManager\Security\Form;

use Exception;
use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;

class LoginForm extends ClientFormBuilder implements ClientFormBuilderInterface
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
            ->add($this->blueprint->email('email', ['uk-form-width-large']))
            ->add($this->blueprint->password(
                'password_hash',
                ['uk-form-width-large'],
                null,
                'autocomplete',
                true),
                NULL,
                $this->blueprint->settings(false, null, true, 'Password')
            )
            ->add(
                $this->blueprint->checkbox(
                    'remember_me',
                    [],
                    false
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->add(
                $this->blueprint->submit(
                    'index-security',
                    ['uk-button', 'uk-button-primary'],
                    'Login'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true, 'Remember Me')
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}
