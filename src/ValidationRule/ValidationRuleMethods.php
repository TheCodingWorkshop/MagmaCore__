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

namespace MagmaCore\ValidationRule;

use MagmaCore\Error\Error;

class ValidationRuleMethods
{

    /** @var string $key */
    protected string $key;
    /** @var mixed $value */
    protected mixed $value;
    /** @var object $model */
    protected object $model;
    /** @var object $controller */
    protected object $controller;

    /**
     * Main constructor class
     *
     * @param string $key
     * @param mixed $value
     * @param object $model
     * @param object $controller
     */
    public function __construct(string $key, mixed $value, object $model, object $controller)
    {
        if ($key)
            $this->key = $key;
        if ($value)
            $this->value = $value;
        if ($model)
            $this->model = $model;
        if ($controller)
            $this->controller = $controller;
    }

    /**
     * Field is required validation rule
     *
     */
    public function required()
    {
        if (isset($this->key)) {
            if (empty($this->value) or $this->value === '') {
                $this->dispatchError(Error::display('err_field_require'));
            }
        }
    }

    /**
     * validation rule which checks the database for duplicate entry
     *
     * @return void
     */
    public function unique()
    {
        if (isset($this->key)) {
            $result = $this->model->findObjectBy([$this->key => $this->value], ['id']);
            if ($result) {
                $this->dispatchError(Error::display($this->value . 'err_data_exists'));
            }
        }
    }

    /**
     * valid email address require validation rule
     *
     * @return void
     */
    public function email()
    {
        if (isset($this->key)) {
            if (filter_var($this->value, FILTER_VALIDATE_EMAIL) === false) {
                $this->dispatchError(Error::display('err_invalid_email'));
            }
        }
    }

    public function length($length)
    {
        if (!empty($this->value)) {
            if (strlen($this->value) < $length) {
                $this->dispatchError(Error::display('err_password_length'));
            }
        }
    }

    public function numberChar()
    {
        if (!empty($this->value)) {
            if (preg_match('/.*\d+.*/i', $this->value) == 0) {
                $this->dispatchError(Error::display('err_password_number'));
            }
        }
    }

    public function textChar()
    {
        if (!empty($this->value)) {
            if (preg_match('/.*[a-z]+.*/i', $this->value) == 0) {
                $this->dispatchError(Error::display('err_password_letter'));
            }
        }
    }


    /**
     * Dispatch the validation error
     *
     * @param array $msg
     * @return void
     */
    public function dispatchError(array $msg)
    {
        if ($this->controller->error) {
            $this->controller->error->addError($msg, $this->controller)->dispatchError();
        }
    }

}
