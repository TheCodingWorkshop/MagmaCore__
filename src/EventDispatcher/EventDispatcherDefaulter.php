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

namespace MagmaCore\EventDispatcher;

class EventDispatcherDefaulter
{

    public const DEFAULT_MESSAGES = [
        'new_password' => 'Your request was successful. Please check your email address for reset link',
        'password_reset' => 'Password reset successfully.',
        'new_activation' => 'You\'re now activated',
    ];

}