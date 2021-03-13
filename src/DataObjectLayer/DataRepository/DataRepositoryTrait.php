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

use MagmaCore\Base\BaseApplication;
use MagmaCore\Collection\Collection;
use MagmaCore\DataObjectLayer\Exception\DataLayerException;
use MagmaCore\DataObjectLayer\Exception\DataLayerUnexpectedValueException;
use MagmaCore\DataObjectLayer\DataRepository\DataRepositoryValidationInterface;

trait DataRepositoryTrait
{

    /**
     * Validate a model before persisting data to the database. These models are 
     * auto loaded once they are created. Models must follow the framework principle
     * meaing model should adopt a naming convention ie. if your model is called UserModel
     * then your validation class should be called UserValidate and should be located under 
     * namespace App\Validate. This directory is located within your App directory
     * within the Validate sub-directory.
     * 
     * Validation classes must extends AbstractDataRepositoryValidation and will be force to
     * employ the following methods (validateBeforePersist(), getErrors(), fields() validate()
     * validateDataBag()). Developers
     * can create their own helper methods to help with validating your data.
     * 
     * The AbstractDataRepositoryValidation() class also provides some small helper method which
     * will be accessible within your validation class so long as you extends this class within 
     * yours. Please see documentation for all available helper methods within this class. for
     * more on what they do and how you can use them
     * 
     * this method returns an array of the validated data and any errors that was generated. You can 
     * also fill your data bag with what ever data you want to return or expose to your controller
     * objects for event dispatching
     *
     * @param Collection $entityCollection
     * @param string $entityObject - use to create the validation object namespace
     * @param Object|null $dataRepository
     * @return self
     */
    public function validateRepository(Collection $entityCollection, string $entityObject, ?Object $dataRepository = null) : self
    {
        if (is_string($entityObject) && !empty($entityObject)) {
            switch ($entityObject) :
                case $entityObject :
                    $validationClassName = str_replace('Entity', 'Validate', $entityObject);
                    if ($validationClassName) {
                        $newValidationObject = BaseApplication::diGet($validationClassName);
                        if (!$newValidationObject instanceof DataRepositoryValidationInterface) {
                            throw new DataLayerUnexpectedValueException($validationClassName . ' is not a valid data repository validation object.');
                        }
                        list(
                            $this->cleanData, 
                            $this->validatedDataBag) = $newValidationObject->validateBeforePersist($entityCollection, $dataRepository);
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
     * Save the data once it goes through validation. post data would have already
     * been sanitize through the entity object
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
                    $this->dataBag = array_merge($this->validatedDataBag);
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
     * Returns an array of validated data which can be passed back to the controller 
     * classes which can then be injected within an event dispatcher
     *
     * @return array|null
     */
    public function validatedDataBag() : array|null
    {
        if (is_array($this->cleanData) && count($this->cleanData) > 0) {
            return $this->dataBag;
        }
        return null;
    }

    /**
     * return an array validation errors from any App/Validation/*Validate class
     *
     * @return array|null
     */
    public function getValidationErrors() : array|null
    {
        if (count($this->validationErrors) > 0) {
            return $this->validationErrors;
        }
        return null;
    }

}