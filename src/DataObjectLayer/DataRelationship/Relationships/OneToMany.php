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

namespace MagmaCore\DataObjectLayer\DataRelationship\Relationships;

use MagmaCore\DataObjectLayer\ClientRepository\ClientRepositoryFactory;
use MagmaCore\DataObjectLayer\DataRelationship\DataRelationship;
use MagmaCore\DataObjectLayer\DataRelationship\DataLayerClientFacade;

/**
 * Both tables can have only one record on each side of the relationship.
 * each primary key value relates to none or only one record in the related table
 */
class OneToMany extends DataRelationship
{

    public function __construct()
    {
    }

    public function findObjectBy()
    {

    }
}