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
    public $file_name;
    public $file_type;
    public $file_size;
    public $tgl_buat;
    public $tgl_update;
    public $users_id;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function dataSesuaiUsersId($users_id, $filter_q)
    {
        $where = "";

        if ($filter_q != "") {
            $where = " and galeri.nama LIKE :filter_q";
        }

        $query = "SELECT galeri.*, users.nama as users_nama, users.level as users_level
        FROM galeri
        INNER JOIN users ON galeri.users_id = users.id
        WHERE galeri.users_id=:users_id $where";
        $this->db->query($query);
        $this->db->bind('users_id', $users_id);
        if ($filter_q != "") {
            $this->db->bind('filter_q', "%$filter_q%");
        }
        return $this->db->resultSet();
    }

    public function cariNamaSama($nama)
    {
        $query = "SELECT *
        FROM galeri
        WHERE nama = :nama";
        $this->db->query($query);
        $this->db->bind('nama', $nama);
        return $this->db->single();
    }

    public function findLikeNama($nama)
    {
        $query = "SELECT galeri.*, users.nama as users_nama, users.level as users_level,
        FROM galeri
        INNER JOIN users ON galeri.users_id = users.id
        WHERE galeri.nama LIKE :nama";
        $this->db->query($query);
        $this->db->bind('nama', "%$nama%");
        return $this->db->single();
    }

    /**
     * form_data []
     */
    public function data_baru($form_data)
    {
        try {
            // untuk kolom id tidak kita cantumkan, karena penambahan nilai id sudah diatasi oleh mysql database
            $query = "INSERT INTO $this->table_name 
            (nama, file, file_name, file_type, file_size, tgl_buat, tgl_update, users_id) 
            VALUES (:nama, :file, :file_name, :file_type, :file_size, :tgl_buat, :tgl_update, :users_id)";

            $this->db->query($query);
            $this->db->bind('nama', $form_data['nama']);
            $this->db->bind('file', $form_data['file']);
            $this->db->bind('file_name', $form_data['file_name']);
            $this->db->bind('file_type', $form_data['file_type']);
            $this->db->bind('file_size', $form_data['file_size']);
            $this->db->bind('tgl_buat', date("Y-m-d H:i:s"));
            $this->db->bind('tgl_update', date("Y-m-d H:i:s"));
            $this->db->bind('users_id', $form_data['users_id']);

            $res = $this->db->execute();
            if ($res === true) {
                $users_id = $this->db->lastInsertId();
                // Mengambil data yang baru disimpan
                return $this->findId($users_id);
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            return $exception;
        }
    }

    public function findId($id)
    {
        $query = "SELECT * FROM galeri WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function findIdAndUsersId($id, $users_id)
    {
        $query = "SELECT * FROM galeri 
         WHERE id=:id and users_id=:users_id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->bind('users_id', $users_id);
        return $this->db->single();
    }

    public function findNamaWithId($id, $nama)
    {
        $query = "SELECT * FROM galeri 
         WHERE id!=:id and nama=:nama";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->bind('nama', $nama);
        return $this->db->single();
    }

    public function merubah_data($id, $form_data)
    {
        try {
            // untuk kolom id tidak kita cantumkan, karena penambahan nilai id sudah diatasi oleh mysql database
            $query = "UPDATE $this->table_name 
            SET nama=:nama, tgl_update=:tgl_update
            WHERE id=:id";

            $this->db->query($query);
            $this->db->bind('id', $id);
            $this->db->bind('nama', $form_data['nama']);
            $this->db->bind('tgl_update', date("Y-m-d H:i:s"));

            $res = $this->db->execute();
            if ($res === true) {
                return $this->findId($id);
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            return $exception;
        }
    }

    public function update_image($id, $form_data)
    {
        try {
            // untuk kolom id tidak kita cantumkan, karena penambahan nilai id sudah diatasi oleh mysql database
            $query = "UPDATE $this->table_name 
            SET file=:file, file_name=:file_name, file_type=:file_type, file_size=:file_size, tgl_update=:tgl_update
            WHERE id=:id";

            $this->db->query($query);
            $this->db->bind('id', $id);
            $this->db->bind('file', $form_data['file']);
            $this->db->bind('file_name', $form_data['file_name']);
            $this->db->bind('file_size', $form_data['file_size']);
            $this->db->bind('file_type', $form_data['file_type']);
            $this->db->bind('tgl_update', date("Y-m-d H:i:s"));

            $res = $this->db->execute();
            if ($res === true) {
                return $this->findId($id);
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            return $exception;
        }
    }

    public function menghapus_data($id)
    {
        try {
            // untuk kolom id tidak kita cantumkan, karena penambahan nilai id sudah diatasi oleh mysql database
            $query = "DELETE FROM $this->table_name WHERE id=:id";

            $this->db->query($query);
            $this->db->bind('id', $id);

            $res = $this->db->execute();
            if ($res === true) {
                return true;
            }
            return false;
        } catch (PDOException $exception) {
            return $exception;
        }
    }
}

?>
