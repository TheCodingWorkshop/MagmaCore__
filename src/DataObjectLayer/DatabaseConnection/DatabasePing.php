<?php

namespace MagmaCore\DataObjectLayer\DatabaseConnection;


class DatabasePing {
    private $pdo;
    private $params;
 
    public function __construct() {
        $this->params = func_get_args();
        $this->init();
    }
 
    public function __call($name, array $args) {
        return call_user_func_array(array($this->pdo, $name), $args);
    }
 
    // The ping() will try to reconnect once if connection lost.
    public function ping() {
        try {
            $this->pdo->query('SELECT 1');
        } catch (\PDOException $e) {
            $this->init();            // Don't catch exception here, so that re-connect fail will throw exception
        }
 
        return true;
    }
 
    private function init() {
        $class = new \ReflectionClass('PDO');
        $this->pdo = $class->newInstanceArgs($this->params);
    }
}