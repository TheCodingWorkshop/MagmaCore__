<?php

namespace MagmaCore\DataObjectLayer\DatabaseConnection;


use ReflectionException;

class DatabasePing {
    private object $pdo;
    private array $params;

    /**
     * @throws ReflectionException
     */
    public function __construct() {
        $this->params = func_get_args();
        $this->init();
    }
 
    public function __call($name, array $args) {
        return call_user_func_array(array($this->pdo, $name), $args);
    }
 
    // The ping() will try to reconnect once if connection lost.
    public function ping(): bool
    {
        try {
            $this->pdo->query('SELECT 1');
        } catch (\PDOException $e) {
            $this->init();            // Don't catch exception here, so that re-connect fail will throw exception
        } catch (ReflectionException $e) {
        }

        return true;
    }

    /**
     * @throws ReflectionException
     */
    private function init() {
        $class = new \ReflectionClass('PDO');
        $this->pdo = $class->newInstanceArgs($this->params);
    }
}