<?php
namespace model;
require_once __DIR__ . "/../config/Database.php";

use Config\{Database};
use PDOException;

class Galeri
{
    private $table_name = "galeri";
    private $db;

    public $id;
    public $nama;
    public $file;
    public $file_type;
    public $file_size;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function findLikeNama($nama) {
        $query = "SELECT galeri.*, users.nama as users_nama, users.level as users_level,
        FROM galeri
        INNER JOIN users ON galeri.users_id = users.id
        WHERE galeri.nama LIKE :nama";
        $this->db->query($query);
        $this->db->bind('nama', "%$nama%");
        return $this->db->single();
    }

    /**
     * form_data [name,email,password(has)]
     */
    public function data_baru($form_data)
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
                $users_id = $this->db->lastInsertId();
                // Mengambil data yang baru disimpan
                $this->db->query("SELECT * FROM $this->table_name WHERE id = :id");
                $this->db->bind('id', $users_id);
                return $this->db->single();
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            return $exception;
        }
    }

    public function findId($users_id)
    {
        $query = "SELECT * FROM galeri WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $users_id);
        return $this->db->single();
    }
}

?>
