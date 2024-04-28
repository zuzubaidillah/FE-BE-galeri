<?php
namespace Config;

use PDO;
use PDOException;

class Database
{
    private $host = "localhost";
    private $db_name = "bookshelf-level2";
    private $username = "root";
    private $password = "";
    public $stmt;
    public $dbh;

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;

        $option = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $this->dbh = new PDO($dsn, $this->username, $this->password, $option);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => $e->getMessage()]);
            die();
        }
    }

    public function query($query)
    {
        try {
            $this->stmt = $this->dbh->prepare($query);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => $e->getMessage()]);
            die();
        }
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value) :
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value) :
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value) :
                    $type = PDO::PARAM_NULL;
                    break;
                default :
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        try {
            $this->stmt->execute();
            return true;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => $e->getMessage()]);
            die();
        }
    }

    public function lastInsertId()
    {
        try {
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => $e->getMessage()]);
            die();
        }
    }

    public function resultSet()
    {
        try {
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => $e->getMessage()]);
            die();
        }
    }

    public function single()
    {
        try {
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => $e->getMessage()]);
            die();
        }
    }

    public function rowCount()
    {
        try {
            return $this->stmt->rowCount();
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => $e->getMessage()]);
            die();
        }
    }
}

?>
