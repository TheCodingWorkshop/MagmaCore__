<?php

namespace MagmaContainer\DI\Example;

class User {

    protected $name;
    
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }

}
