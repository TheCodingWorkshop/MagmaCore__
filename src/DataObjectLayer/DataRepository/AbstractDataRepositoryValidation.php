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

use MagmaCore\Session\SessionTrait;
use MagmaCore\ValidationRule\ValidationRule;

Abstract class AbstractDataRepositoryValidation implements DataRepositoryValidationInterface
{

    protected const FIRST = 0;
    protected const LAST = 1;

    public function __construct()
    {
    }

    /**
     * @inheritdoc
     * 
     * @param object $cleanData - the incoming data
     * @param object|null $dataRepository - the repository for the entity
     * @return mixed
     */
    abstract public function validateBeforePersist(Object $cleanData, ?Object $dataRepository = null);

    /**
     * @inheritdoc
     * 
     * @return array
     */
    abstract public function getErrors() : array;

    /**
     * Allows controller to supply and key/value pair to splice from an array
     * 
     * @param array $elements
     * @return array
     */
    public function splice(array $elements = []) : array
    {
        if (count($elements) > 0) {
            return $elements;
        } else {
            return [];
        }
    }

    /**
     * Whilst this is not absolutely necessary as our newCleanData array would
     * have emitted this to prevent it persisting to the database and causing an
     * error. We will however just remove it from here
     *
     * @param array $cleanData
     * @return array
     */
    public function getCsrf(array $cleanData)
    {
        $csrf = [
            '_CSRF_INDEX' => $cleanData['_CSRF_INDEX'],
            '_CSRF_TOKEN' => $cleanData['_CSRF_TOKEN'],
        ];

        return array_diff_key($cleanData, !empty($this->splice()) ? array_merge($csrf, $this->splice()) : $csrf);
    }

    /**
     * Undocumented function
     *
     * @param array $args
     * @param string $key
     * @param integer $flag
     * @return void
     */
    public function getArrayPosition(array $args, string $key, int $flag = self::FIRST)
    {
        if (isset($args[$key]) && $args[$key] !=='') {
            $parts = explode(' ', $args[$key]);
            if ($parts) {
                switch ($flag) {
                    case 0 :
                        $index = $parts[array_key_first($parts)];
                        break;
                    case 1 :
                        $index = $parts[array_key_last($parts)];
                        break;
                }
            }
        } else {
            $index = $args[$key];
        }

        return $index;
    }

    /**
     * Undocumented function
     *
     * @param array $cleanData
     * @param string $field
     * @param mixed $default
     * @return mixed
     */
    public function setDefaultValue(array $cleanData, string $field, $default)
    {
        $value = $default;
        if (isset($cleanData[$field]) && $cleanData[$field] !='') {
            $value = $cleanData[$field];
        }

        if ($value) {
            return $value;
        }
    }

    public function getCreatedBy(array $cleanData)
    {
        $createdById = $this->setDefaultValue($cleanData, 'created_byid', SessionTrait::sessionFromGlobal()->get('user_id') ?? 0);
        return $createdById;
    }
    
    /**
     * Contains an array of returned data from the fields() method and merges it with an 
     * array pass to the argument within this getAttr(array $data) method. 
     * The fields() method is an abstract method defined within AbstractDataRepositoryValidation()
     * and should be employed within each App/Validation/**Validation class 
     * example. array_merge($this->fields(), $data)
     * 
     * @param $cleanData
     * @return array
     */
    protected function mergeWithFields(array $cleanData): array
    {
        $cleanData = (!empty($this->fields()) ? array_merge($cleanData, $this->fields()) : $cleanData);
        return $cleanData;
    }

    /**
     * Check if the data is set before passing to the database handler. This helps to 
     * prevent 'undefined index' errors. We can also pass back default values using the 
     * third argument. Useful when updating records as we can pass back default values which
     * should prevent database field from accidently being changed to a different value as we 
     * are passing back the same value if nothing was set or changed within the submitted form.
     *
     * @param string $key
     * @param mixed $cleanData
     * @param mixed $dataRepository
     * @return mixed
     */
    public function isSet(string $key, mixed $cleanData, mixed $dataRepository = null): mixed
    {
        if (is_object($cleanData)) {
            return isset($cleanData->$key) ? $cleanData->$key : (($dataRepository !== null) ? $dataRepository->$key : null);
        } elseif (is_array($cleanData)) {
            return array_key_exists($key, $cleanData) ? $cleanData[$key] : (($dataRepository !== null) ? $dataRepository->$key : null);
        } else {
            return $cleanData[$key];
        }
    }

    public function errorIfExists(string $model, string $fieldName, mixed $value)
    {
        if (is_string($fieldName)) {
            $result = (new $model())->getRepo()->findObjectBy([$fieldName => $value], ['*']);
            if ($result) {
                $this->errors['err_duplicate_name'] = str_replace('_', ' ', ucwords($fieldName)) . ' already exists';
            }
        }

    }


}