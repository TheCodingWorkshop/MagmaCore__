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

namespace MagmaCore\Ticket;

use MagmaCore\Base\AbstractBaseModel;
use MagmaCore\Utility\UtilityTrait;

class TicketModel extends AbstractBaseModel
{

    use UtilityTrait;

    /** @var string */
    protected const TABLESCHEMA = 'tickets';
    /** @var string */
    protected const TABLESCHEMAID = 'id';
    /** @var array - field casting */
    protected array $cast = [];
    /* @var array COLUMN_STATUS */
    public const COLUMN_STATUS = [];

    /** @var array $fillable - an array of fields that should not be null */
    protected array $fillable = [];

    /**
     * Main constructor class which passes the relevant information to the
     * base model parent constructor. This allows the repository to fetch the
     * correct information from the database based on the model/entity
     *
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function __construct()
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, TicketEntity::class);

    }

    /**
     * Guard these IDs from being deleted etc..
     *
     * @return array
     */
    public function guardedID(): array
    {
        return [];
    }

    /**
     * Return an array of column values if table supports the column field
     *
     * @return array
     */
    public function getColumnStatus(): array
    {
        return self::COLUMN_STATUS;
    }

    public function ticketStatusMenu(): array
    {
        return [
            'open' => ['name' => 'Open', 'icon' => 'folder-open-outline'],
            'closed' => ['name' => 'Closed', 'icon' => 'send-outline'],
            'resolved' => ['name' => 'Resolved', 'icon' => 'pencil-outline'],
        ];
    }


}

