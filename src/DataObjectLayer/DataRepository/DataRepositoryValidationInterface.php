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
     * returns an array of additional fields we can apply when inserting data within
     * the database. Any data added here should be sanitized and validated as these 
     * will be merge with the returned value of validateBeforePersist() method
     * 
     * @return array
     */
    public function fields() : array;

    /**
     * Returns an array of data which can be accessible from the controller object. The 
     * validated data can be used to populate events for dispatching to other object
     * to modify or used.
     * 
     * @param array $newCleanData
     * @return array
     */
    public function validatedDataBag($newCleanData): array;

    /**
     * The validation method which should be called within the validateBeforePersist() method. 
     * As this needs to be executed before any persisting to database. Use this method to create 
     * your validation rules for your submitted data.
     *
     * @param array $cleanData
     * @param object|null $dataRepository
     * @return void
     */
    public function validate(array $cleanData, object|null $dataRepository = null): array|null;


}