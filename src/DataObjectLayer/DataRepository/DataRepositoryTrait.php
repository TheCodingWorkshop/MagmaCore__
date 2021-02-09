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

use MagmaCore\DataObjectLayer\Exception\DataLayerUnexpectedValueException;
use MagmaCore\DataObjectLayer\Exception\DataLayerException;
use MagmaCore\DataObjectLayer\DataRepository\DataRepositoryValidationInterface;

trait DataRepositoryTrait
{

    /**
     * Undocumented function
     *
     * @param Object $entityCleanData
     * @param Object|null $dataRepository
     * @return self
     */
    public function validateRepository(Object $entityCleanData, ?Object $dataRepository = null) : self
    {
        $entityNamespace = get_class($entityCleanData);
        if (is_string($entityNamespace) && !empty($entityNamespace)) {
            switch ($entityNamespace) :
                case $entityNamespace :
                    $validationClassName = str_replace('Entity', 'Validate', $entityNamespace);
                    if ($validationClassName) {
                        $newValidationObject = new $validationClassName($entityCleanData);
                        if (!$newValidationObject instanceof DataRepositoryValidationInterface) {
                            throw new DataLayerUnexpectedValueException($validationClassName . ' is not a valid data repository validation object.');
                        }
                        list(
                            $this->cleanData, 
                            $this->validatedDataBag,
                            $this->randomPassword) = $newValidationObject->validateBeforePersist($entityCleanData, $dataRepository);
                        $this->validationErrors = $newValidationObject->getErrors();
                            
                    }
                    break;
                default :
                    throw new DataLayerException('Invalid datarepository validation object.');
                    break;
            endswitch;
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $fields
     * @return boolean
     */
    public function saveAfterValidation(array $fields) : bool
    {
        if (empty($this->validationErrors)) {
            if (is_array($fields) && is_array($this->cleanData)) {
                $combinedData = array_merge($fields, $this->cleanData);
                $update = $this->em->getCrud()->update($combinedData, $this->em->getCrud()->getSchemaID());
                if ($update) {
                    return $update;
                }
            }    
        }
        return false;
    }

    /**
     * Persist the sanitized and validated data to the database. Also merge the return last inserted ID
     * along with the validated data. The user activation hash is already part of the returned data array
     * from the validation class.
     * Return all data for the event dispatcher
     *
     * @param array $fields
     * @return boolean
     */
    public function persistAfterValidation(array $fields = []) : bool
    { 
        if (empty($this->validationErrors)) {
            if (is_array($this->cleanData) && count($this->cleanData) > 0) {
                $withOptions = !empty($fields) ? array_merge($fields, $this->cleanData) : $this->cleanData;
                $push = $this->em->getCrud()->create($withOptions);
                if ($push) {
                    /* Populate data bag and return in a separate method validatedDataBag() */
                    $this->dataBag = array_merge($this->validatedDataBag, ['last_id' => $this->em->getCrud()->lastID()]);

                    return $push;
                }
            }
        }
        return false;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function validatedDataBag()
    {
        if (is_array($this->cleanData) && count($this->cleanData) > 0) {
            return $this->dataBag;
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getValidationErrors()
    {
        if (count($this->validationErrors) > 0) {
            return $this->validationErrors;
        }
    }

    public function getRandomPassword()
    {
        return $this->randomPassword;
    }

}