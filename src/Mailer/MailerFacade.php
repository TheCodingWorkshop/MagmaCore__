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

namespace MagmaCore\Mailer;

class MailerFacade
{
    /** @var object */
    protected object $mailer;

    /**
     * Facade main constructor method which creates an object the mailer factory
     * class and pipe the Object to the class property.
     *
     * @param array|null $settings
     */
    public function __construct(?array $settings = null)
    { 
        $this->mailer =  (new MailerFactory($settings))->create(\PHPMailer\PHPMailer\PHPMailer::class);
    }

    /**
     * Quickly send a basic email which comprises of the argument listed in the method
     * below. Note this basic mail method does not send attachments
     *
     * @param string $subject
     * @param string $from
     * @param string $to
     * @param string $message
     * @return mixed
     * @throws Exception\MailerException
     */
    public function basicMail(string $subject, string $from, string $to, string $message): mixed
    {
        return $this->mailer
        ->subject($subject)
        ->from($from)
        ->address($to)
        ->body($message)
        ->send();
        
    }

}