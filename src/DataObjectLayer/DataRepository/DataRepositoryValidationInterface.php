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

namespace MagmaCore\DataObjectLayer\DataRepository;

interface DataRepositoryValidationInterface
{

    /**
     * Validate the data before persisting to the database ensure
     * the entity return valid email and password fields
     * 
     * @param object $cleanData - the incoming data
     * @param object|null $dataRepository - the repository for the entity
     * @return mixed
     */
    public function validateBeforePersist(object $cleanData, ?object $dataRepository = null);

    /**
     * Returns an array of generated errors from the validation method
     * 
     * @return array
     */
    public function getErrors() : array;

    /**
     * additional fields which makes up the entity which wasn't posted
     * by the form
     *
     * @return array
     */
    public function fields() : array;

}