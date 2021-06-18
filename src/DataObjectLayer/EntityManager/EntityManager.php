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

namespace MagmaCore\DataObjectLayer\EntityManager;

class EntityManager implements EntityManagerInterface
{

    /**
     * @var CrudInterface
     */
    protected CrudInterface $crud;

    /**
     * Main constructor class
     * 
     * @return void
     */
    public function __construct(CrudInterface $crud)
    {
        $this->crud = $crud;
    }

    /**
     * @inheritDoc
     */
    public function getCrud() : Object
    {
        return $this->crud;
    }

}
