<?php
namespace Model;
require_once __DIR__ . "/../config/Database.php";

use Config\{Database};
use PDOException;

class Users
{
    private $table_name = "users";
    private $db;

    public $id;
    public $name;
    public $email;
    public $password;
    public $file;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function findEmail($email) {
        $query = "SELECT *
        FROM users
        WHERE email = :email";
        $this->db->query($query);
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    /**
     * form_data [name,email,password(has)]
     */
    public function registrasi($form_data)
    {
        try {
            $query = "INSERT INTO $this->table_name 
            (name, email, password, created_at) 
            VALUES (:name, :email, :password, :created_at)";

            $this->db->query($query);
            $this->db->bind('name', $form_data['name']);
            $this->db->bind('email', $form_data['email']);
            $this->db->bind('password', $form_data['password']);
            $this->db->bind('created_at', date("Y-m-d H:i:s"));

            $res = $this->db->execute();
            if ($res === true) {
                $user_id = $this->db->lastInsertId();
                // Mengambil data yang baru disimpan
                $this->db->query("SELECT * FROM $this->table_name WHERE id = :id");
                $this->db->bind('id', $user_id);
                return $this->db->single();
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            return $exception;
        }
    }

    public function findId($user_id)
    {
        $query = "SELECT * FROM users WHERE id=:id and deleted_at IS NULL";
        $this->db->query($query);
        $this->db->bind('id', $user_id);
        return $this->db->single();
    }
}

?>
