<?php
namespace Model;
require_once __DIR__ . "/../config/Database.php";

use Config\{Database};
use PDOException;

class Users
{
    private $table_name = "users";
    private $db;

    public $result;

    public $id;
    public $name;
    public $email;
    public $password;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function allData() {
        $query = "SELECT id, nama, email, level, tgl_buat, tgl_update
        FROM users
        ORDER BY tgl_buat DESC";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    // fungsi ini akan mengembalikan single()
    // single() jika ditemukan maka mengembalikan berbentuk array
    // single() jika tidak ditemukan maka mengembalikan nilai false
    public function findNama($nama) {
        $query = "SELECT *
        FROM users
        WHERE nama = :nama";
        $this->db->query($query);
        $this->db->bind('nama', $nama);
        return $this->db->single();
    }

    // fungsi ini akan mengembalikan single()
    // single() jika ditemukan maka mengembalikan berbentuk array
    // single() jika tidak ditemukan maka mengembalikan nilai false
    public function findEmail($email) {
        $query = "SELECT *
        FROM users
        WHERE email = :email";
        $this->db->query($query);
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    /**
     * form_data [nama, no_telpon, email, password(has), level]
     */
    public function buat_data_baru($form_data)
    {
        try {
            $query = "INSERT INTO $this->table_name 
            (name, no_telpon, email, password, level, tgl_buat, tgl_update) 
            VALUES (:nama, :nomor, :email, :password, :level, :tgl_buat, :tgl_update)";

            $this->db->query($query);
            $this->db->bind('nama', $form_data['nama']);
            $this->db->bind('nomor', $form_data['no_telpon']);
            $this->db->bind('email', $form_data['email']);
            $this->db->bind('password', $form_data['password']);
            $this->db->bind('level', $form_data['level']);
            $this->db->bind('tgl_buat', date("Y-m-d H:i:s"));
            $this->db->bind('tgl_update', date("Y-m-d H:i:s"));

            $res = $this->db->execute();
            if ($res === true) {
                $users_id = $this->db->lastInsertId();
                // Mengambil data yang baru disimpan
                $this->db->query("SELECT * FROM $this->table_name WHERE id = :id");
                $this->db->bind('id', $users_id);
                $this->result = $this->db->single();
                return true;
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            return $exception;
        }
    }

    public function findId($users_id)
    {
        $query = "SELECT id, nama, email, level, tgl_buat, tgl_update 
        FROM users WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $users_id);
        return $this->db->single();
    }
}

?>
