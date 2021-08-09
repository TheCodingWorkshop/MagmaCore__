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

namespace MagmaCore\Base\Events;

class EventLogger
{

    /* @var array defines the database event log columns */
    public const LOG_FIELDS = [
        'event_log_name',
        'event_type',
        'user',
        'method',
        'source',
        'event_context',
        'event_browser',
        'IP',
    ];

    public const INFORMATION = 'information';
    public const WARNING = 'warning';
    public const CRITICAL = 'critical';
    public const ERROR = 'error';
    public const SYSTEM = 'system';

}