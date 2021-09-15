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

namespace MagmaCore\UserManager\PasswordRecovery;

use MagmaCore\UserManager\UserModel;
use MagmaCore\Auth\Contracts\UserPasswordRecoveryInterface;
use MagmaCore\Base\BaseView;
use MagmaCore\Mailer\Exception\MailerException;
use MagmaCore\Mailer\MailerFacade;
use MagmaCore\Utility\HashGenerator;
use MagmaCore\Utility\Token;
use MagmaCore\Utility\Yaml;
use InvalidArgumentException;
use Exception;

class PasswordRepository extends UserModel implements UserPasswordRecoveryInterface
{
    private mixed $tokenReturned;
    private string $userEmail;
    private $user;

    /**
     * Get the current user object via the method $email argument. Once object is located
     * then the method will assign the $this->userEmail property the method $email argument
     * and return self
     *
     * @param string $email
     * @return self
     */
    public function findByUser(string $email): self
    {
        $user = $this->getRepo()->findObjectBy(['email' => $email], ['email', 'id']);
        if (null !== $user) {
            list($this->tokenReturned) = $this->resetPassword($user->id);
            if (null != $this->tokenReturned) {
                $this->userEmail = $email;
                $this->user = $user;
            }
        }
        return $this;
    }

    /**
     * Construct message for the password recovery
     *
     * @param object $controller
     * @return string
     */
    private function templateMessage(object $controller): string
    {
        $link = $controller->getSiteUrl('/password/reset/' . $this->tokenReturned);
        $html = '<div>';
        $html .= '<h1>' . Yaml::file('app')['password_recovery']['title'] . '</h1>';
        $html .= Yaml::file('app')['password_recovery']['message'];
        $html .= '<br><br><a href="' . $link . '">' . Yaml::file('app')['password_recovery']['call_to_action'] . '</a>';
        $html .= '</div>';
        return $html;
    }

    /**
     * Send the request password recovery email link.
     * 
     * @param object $controller
     * @return bool
     * @throws MailerException
     */
    public function sendUserResetPassword(object $controller): bool
    {
        $mail = (new MailerFacade())->basicMail(
            'Password Reset Requested',
            'admin@example.com',
            $this->userEmail,
            $this->templateMessage($controller)
        );
        if ($mail) {
            return true;
        }
        return false;
    }

    /**
     * Add password reset record to the database table for the current user resetting their password
     * this will capture a reset token hash and the expiry timestamp. To validate against to ensure
     * teh user reset their password in the allotted time. else token will become invalid and the user
     * would off to require a new token hash.
     *
     * @param integer $userID
     * @return array|null
     */
    public function resetPassword(int $userID): ?array
    {
        list($tokenHash, $tokenValue) = (new HashGenerator())->hash();
        $timestampExpiry = time() + 60 * 60 * 2;
        $fields = ['password_reset_hash' => $tokenHash, 'password_reset_expires_at' => date('Y-m-d H:i:s', $timestampExpiry)];
        $this->getRepo()->findByIDAndUpdate($fields, $userID);

        return [$tokenValue];

    }

    /**
     * Find a user by the token hash passed in. If the token hash is valid then and only
     * then will the object be returned
     *
     * @param string|null $tokenHash
     * @return object|null
     * @throws Exception
     */
    public function findByPasswordResetToken(string $tokenHash = null): ?object
    {
        $token = new Token($tokenHash);
        $_tokenHash = $token->getHash();
        $findBy = $this->getRepo()->findObjectBy(['password_reset_hash' => $_tokenHash], ['*']);
        if (null !== $findBy) {
            if (strtotime($findBy->password_reset_expires_at) > time()) {
                return $findBy;
            }
        }
        return null;
    }

    /**
     * This method will clear the database password reset hash back to null and reset the expires at field
     * once the user successfully updates their password. all required field then get reset back to normal
     *
     * @return boolean
     */
    public function reset(): bool
    {
        if (null === $this->tokenRepository) {
            throw new InvalidArgumentException('token repository returning null');
        }
        $fields = ['password_hash' => $this->validatedHashPassword, 'password_reset_hash' => NULL, 'password_reset_expires_at' => NULL];
        $userID = intval($this->tokenRepository->id);
        $update = $this->getRepo()->findByIdAndUpdate($fields, $userID);
        return $update ?? false;
    }

    /**
     * Fetch the user object from the valid token within the url. Method will
     * false if the token has expired
     *
     * @param string $tokenHash - the URL hash token sent to the user who requested it
     * @return object|null
     * @throws Exception
     */
    public function parsedUrlToken(string $tokenHash): ?object
    {
        return $this->findByPasswordResetToken($tokenHash) ?? null;
    }
}
