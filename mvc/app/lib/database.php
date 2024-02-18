<?php

class database {

    private static $instance;
    private $conn;

    private $hostname = "localhost";
    private $username = "root";
    private $password = "";
    private $database_name = "eps_db";

    private function __construct() {
        $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->database_name);

        if ($this->conn->connect_error) {
            die('Failed to connect to MySQL: ' . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

}

?>