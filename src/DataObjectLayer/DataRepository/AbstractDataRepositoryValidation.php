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

use Closure;
use MagmaCore\Utility\Yaml;
use MagmaCore\Session\SessionTrait;
use MagmaCore\Collection\Collection;
use MagmaCore\Utility\RandomCharGenerator;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

Abstract class AbstractDataRepositoryValidation implements DataRepositoryValidationInterface
{

    protected const FIRST = 0;
    protected const LAST = 1;

    /**
     * @inheritdoc
     * 
     * @param Collection $entityCollection - the incoming data as a collection object
     * @param object|null $dataRepository - the repository for the entity
     * @return mixed
     */
    abstract public function validateBeforePersist(Collection $entityCollection, ?object $dataRepository = null): array;

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

    public function getCreator($dataCollection)
    {
        return $this->setDefaultValue($dataCollection, 'created_byid', $_SESSION['user_id'] ?? 0);
    }

    /**
     * Whilst this is not absolutely necessary as our newCleanData array would
     * have emitted this to prevent it persisting to the database and causing an
     * error. We will however just remove it from here
     *
     * @param array $cleanData
     * @return array
     */
    public function getCsrf(array $cleanData): array
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
     * @return string
     */
    public function getArrayPosition(array $args, string $key, int $flag = self::FIRST): string
    {
        $index = '';
        if (isset($args[$key]) && $args[$key] !=='') {
            $parts = explode(' ', $args[$key]);
            if ($parts) {
                $index = match ($flag) {
                    0 => $parts[array_key_first($parts)],
                    1 => $parts[array_key_last($parts)],
                };
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
    public function setDefaultValue(array $cleanData, string $field, mixed $default): mixed
    {
        $value = $default;
        if (isset($cleanData[$field]) && $cleanData[$field] !='') {
            $value = $cleanData[$field];
        }

        if ($value) {
            return $value;
        }
        return false;
    }

    public function getCreatedBy(array $cleanData)
    {
        return $this->setDefaultValue($cleanData, 'created_byid', SessionTrait::sessionFromGlobal()->get('user_id') ?? 0);
    }

    /**
     * Contains an array of returned data from the fields() method and merges it with an
     * array pass to the argument within this getAttr(array $data) method.
     * The fields() method is an abstract method defined within AbstractDataRepositoryValidation()
     * and should be employed within each App/Validation/**Validation class
     * example. array_merge($this->fields(), $data)
     *
     * @param array $cleanData
     * @return array
     */
    protected function mergeWithFields(array $cleanData): array
    {
        return (!empty($this->fields()) ? array_merge($cleanData, $this->fields()) : $cleanData);
    }

    /**
     * Check if the data is set before passing to the database handler. This helps to 
     * prevent 'undefined index' errors. We can also pass back default values using the 
     * third argument. Useful when updating records as we can pass back default values which
     * should prevent database field from accidentally being changed to a different value as we
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
            return $cleanData->$key ?? (($dataRepository !== null) ? $dataRepository->$key : null);
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

    public function dovalidation(Collection $entityCollection, ?object $dataRepository, Closure $callback)
    {
       if (null !== $entityCollection) {
            if (is_object($entityCollection) && $entityCollection->count() > 0) {
                foreach ($entityCollection as $this->key => $this->value) :
                    if (isset($this->key) && $this->key != '') :
                        if (!$callback instanceof Closure) {
                            throw new BaseInvalidArgumentException($callback . ' is not an instance of a closure.');
                        }

                        return $callback($this->key, $this->value, $entityCollection, $dataRepository);
                    endif;
                endforeach;
            }
        }

    }

    /**
     * Return the security options from the app config file
     *
     * @param string $key
     * @return mixed
     */
    private function appSecurity(string $key): mixed
    {
        $app = Yaml::file('app');
        if ($app) {
            return $app['security']['password_algo'];
        }
    }


    /**
     * A user is required to type their password when creating an account on the
     * frontend of the application. However when admin is creating a user from the
     * admin panel. A password will be automatically generated instead and send along
     * with the user activation token via their registered email address. Either way the
     * password will be encoded before pass the database handler
     *
     * @param array $dataCollection
     * @return string
     */
    public function userPassword(array $dataCollection): array
    {
        $userPassword = '';
        $randomPassword = RandomCharGenerator::generate();

        if (array_key_exists('client_password_hash', $dataCollection)) {
            $userPassword = $this->isSet('client_password_hash', $dataCollection);
            $encodedPassword = password_hash(
                $userPassword,
                constant($this->appSecurity('password_algo')['default']),
                $this->appSecurity('hash_cost_factor')
            );
        }
        if (array_key_exists('password_hash', $dataCollection)) {
            $userPassword = $this->isSet('password_hash', $dataCollection);
            $encodedPassword = password_hash(
                empty($userPassword) || $userPassword === '' ? $randomPassword : $userPassword,
                constant($this->appSecurity('password_algo')['default']),
                $this->appSecurity('hash_cost_factor')
            );
        }

        if ($encodedPassword)
            return [
                $encodedPassword,
                array_key_exists('password_hash', $dataCollection) ? $randomPassword : ''
            ];
    }

}