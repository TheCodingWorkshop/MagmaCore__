<?php

namespace MagmaContainer\DI\Example;

class TestClass
{
    protected $user;
    protected $people;
    
    public function __construct(User $user, People $people)
    {
        $this->user = $user;
        $this->people = $people;
    }

    public function getUser()
    {
        return $this->user;
    }

}
