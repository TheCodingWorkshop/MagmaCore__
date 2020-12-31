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

use MagmaCore\FormBuilder\Exception\FormBuilderInvalidArgumentException;
use MagmaCore\FormBuilder\FormBuilder;

class ClientFormBuilder extends FormBuilder
{
    /**
     * @var mixed
     */
    protected $repositoryObject;

    protected string $repositoryObjectName;

    /**
     * Main purpose of this constructor is to provide an easy way for us
     * to access the correct data repository from our form builder class
     * We only need to type hint the class to the parent constructor
     * within the constructor of our form builder class. Only instances of
     * data repository is allowed will throw an exception otherwise
     *
     * @param string $repositoryObjectName - the name of the repository we want to instantiate
     */
    public function __construct(?string $repositoryObjectName = null)
    {
        if ($repositoryObjectName !=null){
            $this->repositoryObjectName = $repositoryObjectName;
            $repositoryObject = new $repositoryObjectName();
            if (!$repositoryObject) {
                throw new FormBuilderInvalidArgumentException('Invalid repository');
            }
            $this->repositoryObject = $repositoryObject;
        }
    }   

    /**
     * Check the repository isn't Null
     *
     * @return boolean
     */
    public function hasRepo() : bool
    {
        if (!$this->repositoryObject) {
            throw new FormBuilderInvalidArgumentException($this->repositoryObjectName .' repository has returned null. Repository is only valid if your editing existing data.');
        }   
        return true;
    }

    /**
     * Return the repository object
     * 
     * @return object
     */
    public function getRepo() : Object
    {
        if ($this->hasRepo()) {
            return $this->repositoryObject;
        }
    }

    /**
     * Cast repository object to an array
     *
     * @param Object $data
     * @return array
     */
    public function castArray(Object $data)
    {
        if ($data !=null) {
            $data = (array)$data;
            return $data;
        }
        return false;
    }


}