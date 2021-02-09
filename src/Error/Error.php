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

namespace MagmaCore\Error;

class Error implements ErrorInterface
{

    protected array $errors = [];
    protected array $errorParams = [];
    protected ?string $errorCode = null;

    public const SHORT_PASSWORD     = 'ERR_100MC';
    public const PASSWORD_LETTER    = 'ERR_150MC';
    public const PASSWORD_NUMBER    = 'ERR_200MC';
    public const INVALID_EMAIL      = 'ERR_250MC';
    public const EMPTY_FIELDS       = 'ERR_300MC';

    /**
     * Add a error to the error array
     *
     * @param array|string $error
     * @param array $errorParams
     * @return void
     */
    public function addError($error, array $errorParams = [])
    {
        if ($error)
            $this->errors = $error;

    }

    public function dispatchError(Object $object)
    {
        if (is_array($this->errors) && count($this->errors) > 0) {
            foreach ($this->errors as $code => $error) {
                if (is_string($code)) {
                    $this->errorCode = $code;
                    $object->flashMessage($error, $object->flashWarning());
                    $object->redirect('/register');
                }
            }
        }

    }

    /**
     * Undocumented function
     *
     * @param integer $errorCode
     * @return boolean
     */
    public function hasError(int $errorCode) : bool
    {
        foreach ($this->errors as $code => $error) {
            if (preg_match($code, $errorCode, $matches)) {
                foreach ($matches as $key => $match) {
                    $params[$key] = $match;
                    $this->errorParams = $params;
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Returns the array of errors
     *
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }

    /**
     * Returns an array of error parameters
     *
     * @return array
     */
    public function getErrorParams() : array
    {
        return $this->errorParams;
    }


    public function getErrorCode() : string
    {
        return $this->errorCode;
    }

}