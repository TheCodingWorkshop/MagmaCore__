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

namespace MagmaCore\FormBuilder\Traits;

use Throwable;
use MagmaCore\Error\Error;
use ParagonIE\AntiCSRF\AntiCSRF;
use MagmaCore\Http\RequestHandler;
use MagmaCore\FormBuilder\Exception\FormBuilderUnexpectedValueException;

trait FormTrait
{

    /**
     * Check the form can be submitted and the request if correct
     *
     * @param string $submit
     * @return boolean
     * @throws Throwable
     */
    public function isFormValid(string $submit): bool
    {
        if ($this->canHandleRequest() && $this->isSubmittable($submit)) {
            return true;
        }
        return false;
    }

    /**
     * Throw an error if the csrf validation fails. 
     *
     * @param object $controller
     * @return void
     */
    public function validateCsrf(object $controller)
    {
        if (!$this->csrfValidate()) {
            if (isset($this->error)) {
                $this->error->addError(Error::display('err_invalid_csrf'), $controller)->dispatchError();
            }
        }
    }

    /**
     * Check whether the request can be handled
     * 
     * @return array
     * @throws Throwable
     */
    public function canHandleRequest() : array
    { 
        $method = ($_SERVER['REQUEST_METHOD'] ?? '');
        if ($method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $method == 'DELETE';
            } elseif ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $method == 'PUT';
            } else {
                throw new FormBuilderUnexpectedValueException('Unexpected Header');
            }
        }
        try {
            return [
                $method,
                (class_exists(RequestHandler::class)) ? (new RequestHandler())->handler()->request->all() : $_POST,
                $this->getStream()
            ];
        } catch (Throwable $th) {
            throw $th->getMessage();
        }
    }

    private function getStream()
    {
        $contentType = isset($_SERVER['CONTENT_TYPE']) && $_SERVER['REQUEST_METHOD'] == 'POST' ? trim($_SERVER['CONTENT_TYPE']) : '';
        if ($contentType === 'application/json') {
            $content = trim(file_get_contents('php://input', false, stream_context_get_default(), 0, $_SERVER['CONTENT_LENGTH']));
            $decode = json_decode($content, true);
            if (is_array($decode)) {
                echo $decode;
            } else {
                throw new FormBuilderUnexpectedValueException('Invalid Data');
            }
        }
    }

    public function getFormAttr(string $attr)
    {
        if ($attr) {
            $field = (new RequestHandler())->handler()->get($attr);
            if ($field) {
                return $field;
            }
        }
    }

    /**
     * Get the request method verb as a string
     * @throws Throwable
     */
    public function getMethod(string $method) : string
    {
        list($_method, $_post, $_json) = $this->canHandleRequest();
        if ($_method === $method) {
            return $_method;
        }
    }

    /**
     * Returns json data
     * @throws Throwable
     */
    public function getJson()
    {
        list($_method, $_post, $_json) = $this->canHandleRequest();
        return $_json;
    }

    /**
     * Returns form data
     * @throws Throwable
     */
    public function getData() : array
    {
        list($_method, $_post, $_json) = $this->canHandleRequest();
        return $_post;

    }

    public function getFile()
    {
        //return (isset($_FILES[$filename]) )
    }

    /**
     * Is the request an ajax request
     * @return boolean
     */
    public function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Check whether the form is submittable. Submit button should represent
     * the argument name
     *
     * @param string $name - default to <input type="submit" name="submit">
     * @return bool
     */
    public function isSubmittable(string $name = 'submit') : bool
    {
        return (isset($_POST[$name]));
    }

    /**
     * Instantiate the external csrf fields
     *
     * @param mixed|null $lock
     * @return string
     * @throws Exception
     */
    public function csrfForm(mixed $lock = null): string
    { 
        static $addCsrf;
        if ($addCsrf === null) {
            $addCsrf = new AntiCSRF();
        }

        return $addCsrf->insertToken($lock, false);

    }

    /**
     * Wrapper function for validating csrf token
     *
     * @return bool
     */
    public function csrfValidate(): bool
    { 
        $addCsrf = new AntiCSRF();
        return $addCsrf->validateRequest();

    }


}