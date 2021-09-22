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

namespace MagmaCore\UserManager\Activation;

use MagmaCore\UserManager\UserModel;
use MagmaCore\Auth\Contracts\UserActivationInterface;
use MagmaCore\Base\BaseView;
use MagmaCore\Mailer\Exception\MailerException;
use MagmaCore\Mailer\MailerFacade;
use MagmaCore\Utility\Token;
use Exception;

class ActivationRepository extends UserModel implements UserActivationInterface
{

    protected array $errors = [];
    private int $userID;
    private array $fields;
    private string $hash;

    /**
     * @param string $token
     * @return ?object
     * @throws Exception
     */
    public function findByActivationToken(string $token): ?object
    {
        $token = new Token($token);
        $_tokenHash = $token->getHash();
        $findBy = $this->getRepo()->findObjectBy(['activation_token' => $_tokenHash], ['*']);
        if (null !== $findBy) {
            return $findBy;
        }

        return null;
    }

    /**
     * @param string $hash
     * @return $this
     * @throws MailerException
     */
    public function sendUserActivationEmail(string $hash): self
    {
        (new MailerFacade())->basicMail(
            'Activate Your Account',
            'admin@magmacore.com',
            '',
            (new BaseView())->templateRender(
                'client/registration/activation_email.html.twig',
                ["url" => "http:/" . $_SERVER['HTTP_HOST'] . "/activation/activate/" . $this->hash]
            )
        );
        return $this;

    }

    /**
     * @param object|null $repository
     * @return $this
     */
    public function validateActivation(?object $repository): self
    {
        if ($repository === null) {
            $this->errors['invalid_account'] = 'Sorry no user was found!';
            //throw new \InvalidArgumentException();
        }
        $this->userID = $repository->id;
        $this->fields = ['activation_token' => NULL, 'status' => 'active'];
        return $this;
    }

    /**
     * @return bool
     */
    public function activate(): bool
    {
        $update = $this->getRepo()->findByIdAndUpdate($this->fields, $this->userID);
        if ($update) {
            return $update;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }


}