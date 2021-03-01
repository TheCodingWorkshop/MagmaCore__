<?php

declare(strict_types=1);

namespace MagmaCore\Utility;

class DirtyConnection
{

    private $db_host;
    private $db_name;
    private $db_user;
    private $db_password;

    public function __construct($db_host, $db_name, $db_user, $db_password)
    {

        $this->db_host = $db_host;
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_password = $db_password;
    }

    public function dirtyConnect()
    {
        if (isset($this->conn))
            return;

        return $this->conn = new \mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);
    }

    public function close()
    {
        $this->conn->close();
    }
}
