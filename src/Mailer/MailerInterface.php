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

use MagmaCore\Mailer\Exception\MailerException;
use MagmaCore\Mailer\Exception\MailerInvalidArgumentException;

interface MailerInterface
{ 

    /**
     * Add the email subject fields and Set email format to HTML
     *
     * @param string $subject
     * @return self
     * @throws MailerInvalidArgumentException
     */
    public function subject(string $subject) : self;

    /**
     * Read an HTML message body from an external file, convert referenced images to embedded,
     * convert HTML into a basic plain-text alternative body
     *
     * @param string $message
     * @return self
     * @throws MailerInvalidArgumentException
     */
    public function body(string $message) : self;

    /**
     * Add a recipient
     *
     * @param string $from
     * @param string|null $name
     * @return self
     * @throws MailerInvalidArgumentException
     */
    public function from(string $from, ?string $name = null) : self;

    /**
     * The recipient address. If we are sending 1 email then that can be passed in
     * as a string but if we are sending multiple emails at the same time. We need
     * to declare the argument as an associative array and pass in the values
     * ['John Doe' => 'johndoe@example.vom', 'Jane Doe' => 'janedoe@example.com']
     * Where the key is the recipient name and the value being the recipient email
     * address.
     *
     * @param mixed|null $args
     * @return self
     */
    public function address(mixed $args = null) : self;

    /**
     * Provide the email field for the recipient to reply to. If required. This would
     * normally be your application admin/technical or whatever email a recipient should
     * reply to under the circumstances. of how the system is being used.
     *
     * @param string $from
     * @param string|null $name
     * @return self
     * @throws MailerInvalidArgumentException
     */
    public function toReply(string $from, ?string $name = null) : self;

    /**
     * Send a carbon copy of the email to the relevant email address supplied
     * within the method argument. The primary purpose of this cc field is
     * to keep someone in the loop.
     *
     * @param string $cc
     * @return self
     * @throws MailerInvalidArgumentException
     */
    public function cc(string $cc) : self;

    /**
     * Send a blind copy of the email. Meaning the recipient won't know who got 
     * copied into the email chat
     *
     * @param string $bcc
     * @return self
     * @throws MailerInvalidArgumentException
     */
    public function bcc(string $bcc) : self;

    /**
     * Add 1 or more attachments to a email that is being sent. Like the address method
     * above if we are only sending 1 attachment. Then we can simple pass it in the method
     * argument. However if we sending multiply attachments we need to declare the argument
     * as an array and pass those in 
     * ['me' => my_photo.jpg, 'you' => 'your_photo.png']
     *
     * @param mixed|null $args
     * @return self
     * @throws MailerInvalidArgumentException
     */
    public function attachments(mixed $args = null) : self;

    /**
     * The method which invokes the mail function which ultimately sends our email
     * base on our email server settings. Which can be completely modified on a mail 
     * by mail basis. Even though there should never be a any need to do that.
     *
     * @param string|null $successMsg
     * @param boolean $saveMail
     * @return mixed
     * @throws MailerException
     */
    public function send(?string $successMsg = null, bool $saveMail = false): mixed;

}