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

use MagmaCore\Mailer\Exception\MailerInvalidArgumentException;
use MagmaCore\Mailer\MailerInterface;

class MailerFactory
{

    /** @var array|null */
    protected ?array $settings = null;

    /**
     * Factory main contructor method. Which allows application to define their own
     * mail server settings. Through the constructor argument
     *
     * @param array|null $settings
     */
    public function __construct(?array $settings = null)
    {
        $this->settings = $settings;
    }

    /**
     * Create method which creates the Mailer object and inject the relevant arguments
     *
     * @param string $transportString
     * @return MailerInterface
     * @throws MailerInvalidArgumentException
     */
    public function create(string $transportString) : MailerInterface
    {
        $this->transporterObject = new $transportString(true);
        if (!$this->transporterObject) {
            throw new MailerInvalidArgumentException($transportString . ' is not a valid mailer object');
        }
        return new Mailer(
            $this->transporterObject, 
            $this->settings, 
            \Symfony\Component\Dotenv\Dotenv::class
        );
    }
}