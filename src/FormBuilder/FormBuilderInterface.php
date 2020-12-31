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

namespace MagmaCore\FormBuilder;

interface FormBuilderInterface
{

    /**
     * Undocumented function
     *
     * @param array $args
     * @return void
     */
    public function form(array $args = []) : self;

    /**
     * This method allows us to chain multiple input types together to build the required
     * form structure
     *
     * @param array $args - optional argument to modified the values of the input wrapping tag
     * @param null $options
     * @return mixed
     */
    public function add(array $args = [], $options = null, array $settings = []) : self;

    /**
     * This methods get chain at the very end after each add() method. And will attemp to build
     * the required input based on each add() method arguments. Theres an option to have
     * HTML elemets wrap around each input tag for better styling of each element
     *
     * @return mixed
     */
    public function build(?array $args = null);

    /**
     * @param Object $request
     * @return array
     */
    public function canHandleRequest(Object $request = null) : array;

    /**
     * Check whether the form is submittable. Submit button should represent
     * the argument name
     *
     * @param string $name - default to <input type="submit" name="submit">
     * @return bool
     */
    public function isSubmittable(string $name = 'submit') : bool;

    /**
     * Instantiate the external csrf fields
     *
     * @param mixed $lock
     * @return bool
     */
    public function csrfForm($lock = null);

    /**
     * Wrapper function for validating csrf token
     *
     * @return bool
     */
    public function csrfValidate();

}