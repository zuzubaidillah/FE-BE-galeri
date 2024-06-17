<?php

namespace Controllers;
require_once __DIR__ . "/../../model/Users.php";
require_once __DIR__ . "/../../config/TokenJwt.php";

use Config\TokenJwt;
use Model\Users;

class UsersController
{
    public function index_ambil_data($current_users) {
        $filter_q = "";
        if (isset($_GET['filter_q'])) {
            $filter_q = $_GET['filter_q'];
        }
        $user = new Users();
        $result = $user->allData($filter_q);
        echo json_encode(['data' => $result]);
    }

    public function show_detail_data($users_id) {
        $user = new Users();
        $result = $user->findId($users_id);
        if ($result === false) {
            http_response_code(404);
            //echo json_encode(['message' => 'Data '.$users_id.' tidak ditemukan']);
            echo json_encode(['message' => "Data $users_id tidak ditemukan"]);
            exit();
        }
        echo json_encode(['data' => $result]);
    }

    /*methode ini masih sangat sederhana dalam melakukan format nomor telphone*/
    private function format_nomor($nomor) {
        // Cek apakah nomor telepon dimulai dengan '0'
        if (substr($nomor, 0, 1) === '0') {
            // Mengganti '0' dengan '62'
            $nomor = '62' . substr($nomor, 1);
        }
        return $nomor;
    }

    public function store_buat_data_baru($current_users) {
        // menerima request dari client content-type: JSON
        $request = json_decode(file_get_contents('php://input'), true);

        // cel validasi request dari client
        // empty() adalah melakukan pendeteksi terhadap nilai yang dikirim apakah bernilai empty/kosong
        if (empty($request['nama']) || empty($request['no_telpon']) || empty($request['level']) || empty($request['email']) || empty($request['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Data tidak lengkap']);
            exit();
        }

        $user = new Users();

        // cek nama yang sama
        $cek_nama_sama = $user->findNama($request['nama']);
        if ($cek_nama_sama !== false) {
            http_response_code(400);
            echo json_encode(['message' => 'Nama sudah digunakan']);
            exit();
        }

        // cek email yang sama
        $cek_email_sama = $user->findEmail($request['email']);
        if ($cek_email_sama !== false) {
            http_response_code(400);
            echo json_encode(['message' => 'Email sudah digunakan']);
            exit();
        }

        $format_no_telpon = $this->format_nomor($request['no_telpon']);
        $password_hased = password_hash($request['password'], PASSWORD_DEFAULT);
        $form_data = [
            'nama' => $request['nama'],
            'no_telpon' => $format_no_telpon,
            'email' => $request['email'],
            'password' => $password_hased,
            'level' => $request['level'],
        ];

        $result = $user->buat_data_baru($form_data);
        if ($result === false) {
            http_response_code(404);
            echo json_encode(['message' => 'Data tidak ditemukan']);
            exit();
        }

        http_response_code(200);
        echo json_encode([
            'message' => 'berhasil',
            'data' => $user->result
        ]);
    }

    public function update_merubah_data($param_users_id, $current_users) {
        // menerima request dari client content-type: JSON
        $request = json_decode(file_get_contents('php://input'), true);

        // cel validasi request dari client
        // empty() adalah melakukan pendeteksi terhadap nilai yang dikirim apakah bernilai empty/kosong
        if (empty($request['nama']) || empty($request['no_telpon']) || empty($request['level']) || empty($request['email'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Data tidak lengkap']);
            exit();
        }

        // jika password dikirim
        if (isset($request['password']) && empty($request['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Data tidak lengkap']);
            exit();
        }

        $model_user = new Users();

        // cek params users_id yang akan diupdate datanya
        $cek_id = $model_user->findId($param_users_id);
        if ($cek_id == false) {
            http_response_code(404);
            echo json_encode(['message' => "Data $param_users_id tidak ditemukan"]);
            exit();
        }

        // cek nama yang sama
        $cek_nama_sama = $model_user->findNamaWithId($param_users_id, $request['nama']);
        if ($cek_nama_sama !== false) {
            http_response_code(400);
            echo json_encode(['message' => 'Nama sudah digunakan']);
            exit();
        }

        // cek email yang sama
        $cek_email_sama = $model_user->findEmailWithId($param_users_id, $request['email']);
        if ($cek_email_sama !== false) {
            http_response_code(400);
            echo json_encode(['message' => 'Email sudah digunakan']);
            exit();
        }

        $format_no_telpon = $this->format_nomor($request['no_telpon']);
        $form_data = [
            'nama' => $request['nama'],
            'no_telpon' => $format_no_telpon,
            'email' => $request['email'],
            'level' => $request['level'],
        ];
        // jika password dikirim
        if (isset($request['password'])) {
            $form_data['password'] = password_hash($request['password'], PASSWORD_DEFAULT);
        }

        $result = $model_user->merubah_data($param_users_id, $form_data);
        if ($result === false) {
            http_response_code(404);
            echo json_encode(['message' => 'Proses update data gagal']);
            exit();
        }

        http_response_code(200);
        echo json_encode([
            'message' => 'berhasil',
            'data' => $model_user->result
        ]);
    }

    public function delete_menghapus_data($param_users_id, $current_users) {

        $model_user = new Users();

        // cek params users_id yang akan dihapus datanya
        $cek_id = $model_user->findId($param_users_id);
        if ($cek_id == false) {
            http_response_code(404);
            echo json_encode(['message' => "Data $param_users_id tidak ditemukan"]);
            exit();
        }

        $result = $model_user->menghapus_data($param_users_id);
        if ($result === false) {
            http_response_code(404);
            echo json_encode(['message' => 'Proses menghapus data gagal']);
            exit();
        }

        http_response_code(200);
        echo "";
    }
}