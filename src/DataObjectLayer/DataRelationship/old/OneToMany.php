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

namespace MagmaCore\DataObjectLayer\DataRelationship;

use MagmaCore\Base\BaseApplication;

/** 
 * The primary key table contains only one record that relates to none, 
 * one, or many records in the related table.
 */
class OneToMany extends AbstractDataRelationship
{

    /**
     * Undocumented function
     *
     * @param string $belongsTo
     * @return void
     */
    public function belongsTo(string $belongsTo): static
    {
        if ($belongsTo)
            $this->belongsTo = BaseApplication::diGet($belongsTo);

        return $this;
    }


    public function getBelongsToSchema(): string
    {
        return $this->belongsTo->getSchema();
    }

}
