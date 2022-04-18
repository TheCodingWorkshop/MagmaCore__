<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types=1);

namespace MagmaCore\UserManager\PasswordRecovery\Form;

use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;
use MagmaCore\FormBuilder\Type\HiddenType;
use MagmaCore\FormBuilder\Type\PasswordType;
use MagmaCore\FormBuilder\Type\SubmitType;
use Exception;

class ResetForm extends ClientFormBuilder implements ClientFormBuilderInterface
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
     * @param string $action
     * @param object|null $dataRepository
     * @param object|null $callingController
     * @return string
     * @throws Exception
     */
    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null): string
    {

        return $this->form(['action' => $action, 'class' => 'uk-form-stacked'])
            ->addRepository($dataRepository)
            ->add(
                $this->blueprint->password('password_hash', ['uk-form-width-large']),
                null,
                $this->blueprint->settings(false, null, true, 'New Password')
            )
            ->add($this->blueprint->hidden('token', $dataRepository->token), null, ['show_label' => false])
            ->add(
                $this->blueprint->submit(
                    'reset-password',
                    ['uk-button', 'uk-button-secondary'],
                    'Reset Password'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);

    }

}
