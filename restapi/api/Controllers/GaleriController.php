<?php

namespace Controllers;
require_once __DIR__ . "/../../model/Users.php";
require_once __DIR__ . "/../../model/Galeri.php";
require_once __DIR__ . "/../../config/TokenJwt.php";

use Config\TokenJwt;
use Exception;
use Model\Users;
use Model\Galeri;

class GaleriController
{
    public function index_ambil_data($current_users_id)
    {
        $filter_q = "";
        if (isset($_GET['filter_q'])) {
            $filter_q = $_GET['filter_q'];
        }
        $user = new Galeri();
        $result = $user->dataSesuaiUsersId($current_users_id, $filter_q);
        echo json_encode(['data' => $result]);
    }

    public function store_buat_data_baru($current_users_id)
    {

        // validasi request client
        $errors = [];
        // Validasi 'title' required
        if (empty($_POST['nama'])) {
            $errors['nama'] = 'nama hatus diisi.';
        }

        // Validasi 'file' required
        if (!isset($_FILES['image'])) {
            $errors['image'] = 'Image harus diisi.';
        } else if ($_FILES['image']['name'] === "") {
            $errors['image'] = 'Image harus diisi.';
        }

        if (count($errors) >= 1) {
            http_response_code(400); // Unauthorized
            echo json_encode([
                'message' => 'data tidak lengkap',
            ]);
            exit();
        }

        // cek title/judul yang sama
        $model_galeri = new Galeri();
        // cek title sama
        $find_book = $model_galeri->cariNamaSama($_POST['nama']);
        if ($find_book) {
            header('Content-Type: application/json');
            http_response_code(400); // Unauthorized
            echo json_encode([
                'message' => "Nama sudah digunakan",
            ]);
            exit();
        }

        // cek ukuran image maksimal 2mb
        if (!$_FILES['image']['size'] || $_FILES['image']['size'] > (2 * 1024 * 1024)) {
            header('Content-Type: application/json');
            http_response_code(400); // Unauthorized
            echo json_encode([
                'message' => 'Ukuran file terlalu besar. Maksimal 2MB.',
            ]);
            exit();
        }

        // file harus jpg, jpeg, png, dan pdf
        $target_folder = 'uploads/';
        $nama_file = basename($_FILES['image']['name']);
        // Menghasilkan nama file unik
        $tipe_file = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $nama_file = uniqid() . '.' . $tipe_file;
        $kombinasi_target_file_name = $target_folder . $nama_file;

        // daftar type file yang diperbolehkan
        $tipe_file_sesuai = ['jpg', 'jpeg', 'png', 'pdf'];

        // cek tipe file
        if (!in_array(strtolower($tipe_file), $tipe_file_sesuai)) {
            header('Content-Type: application/json');
            http_response_code(400); // Unauthorized
            echo json_encode([
                'message' => 'File harus berupa: jpg, jpeg dan png',
            ]);
            exit();
        }

        try {

            // cek apakah ada folder uploads, buatkan folder jika blm ada
            if (!file_exists($target_folder)) {
                mkdir($target_folder, 0777, true);
            }

            // lakukan upload file kedalam folder uploads
            if (move_uploaded_file($_FILES['image']['tmp_name'], $kombinasi_target_file_name)) {
                // lanjut proses simpan
                $nama = $_POST['nama'];

                $form_data = [
                    'nama' => $nama,
                    'file' => $kombinasi_target_file_name,
                    'file_name' => $_FILES['image']['name'],
                    'file_type' => $tipe_file,
                    'file_size' => $_FILES['image']['size'],
                    'users_id' => $current_users_id
                ];

                // simpan data
                $model_galeri = new Galeri();
                $result = $model_galeri->data_baru($form_data);
                header('Content-Type: application/json');
                http_response_code(200);
                // response data yang baru saja disimpan
                echo json_encode([
                    'data' => $result
                ]);
                exit();
            } else {
                header('Content-Type: application/json');
                http_response_code(400); // Unauthorized
                echo json_encode([
                    'message' => 'Maaf, terjadi kesalahan saat mengupload file',
                ]);
                exit();
            }

        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
            exit();
        }
    }

    public function show_detail_data($param_users_id, $current_users_id)
    {
        $model_galeri = new Galeri();
        $result = $model_galeri->findIdAndUsersId($param_users_id, $current_users_id);
        if ($result === false) {
            http_response_code(404);
            //echo json_encode(['message' => 'Data '.$param_users_id.' tidak ditemukan']);
            echo json_encode(['message' => "Data $param_users_id tidak ditemukan"]);
            exit();
        }
        echo json_encode(['data' => $result]);
    }

    public function update_merubah_data($param_galeri_id, $current_users_id)
    {
        // menerima request dari client content-type: JSON
        $request = json_decode(file_get_contents('php://input'), true);

        // cel validasi request dari client
        // empty() adalah melakukan pendeteksi terhadap nilai yang dikirim apakah bernilai empty/kosong
        if (empty($request['nama'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Data tidak lengkap']);
            exit();
        }

        $model_galeri = new Galeri();

        // cek params users_id yang akan diupdate datanya
        $cek_id = $model_galeri->findIdAndUsersId($param_galeri_id, $current_users_id);
        if ($cek_id == false) {
            http_response_code(404);
            echo json_encode(['message' => "Data $param_galeri_id tidak ditemukan"]);
            exit();
        }

        // cek nama yang sama
        $cek_nama_sama = $model_galeri->findNamaWithId($param_galeri_id, $request['nama']);
        if ($cek_nama_sama !== false) {
            http_response_code(400);
            echo json_encode(['message' => 'Nama sudah digunakan']);
            exit();
        }

        $form_data = [
            'nama' => $request['nama']
        ];

        $result = $model_galeri->merubah_data($param_galeri_id, $form_data);
        if ($result === false) {
            http_response_code(404);
            echo json_encode(['message' => 'Proses update data gagal']);
            exit();
        }

        http_response_code(200);
        echo json_encode([
            'message' => 'berhasil',
            'data' => $result
        ]);
    }

    public function update_image($params_galeri_id, $current_users_id)
    {
        // Validasi 'file' optional
        if (!isset($_FILES['image']) || $_FILES['image']['name'] === "") {
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                "message" => "data tidak lengkap"
            ]);
            exit();
        }

        // memanggil object Book (model dari table book)
        $model_galeri = new Galeri();

        // mengambil data by id
        $find_galeri = $model_galeri->findIdAndUsersId($params_galeri_id, $current_users_id);
        if (!$find_galeri) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                "message" => "Buku id $params_galeri_id tidak ditemukan",
            ]);
            exit();
        }

        // Hapus file lama jika ada
        $oldFilePath = $find_galeri['file'];
        if ($oldFilePath != null) {
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        $target_folder = "uploads/";
        $nama_file = basename($_FILES["image"]["name"]);
        // Menghasilkan nama file unik
        $tipe_file = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $nama_file = uniqid() . '.' . $tipe_file;
        $kombinasi_target_file_name = $target_folder . $nama_file;

        // Daftar ekstensi file yang diperbolehkan
        $tipe_file_sesui = ['jpg', 'jpeg', 'png', 'pdf'];

        // Mengecek tipe file
        if (!in_array(strtolower($tipe_file), $tipe_file_sesui)) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                "message" => "Tipe file tidak diperbolehkan. Hanya jpg, jpeg, png."
            ]);
            exit();
        }

        // Cek apakah direktori uploads ada, jika tidak buat direktori tersebut
        if (!file_exists($target_folder)) {
            mkdir($target_folder, 0777, true);
        }

        try {
            // Upload file ke direktori tujuan
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $kombinasi_target_file_name))
            {
                $form_data = [
                    "file" => $kombinasi_target_file_name,
                    "file_name" => $_FILES["image"]["tmp_name"],
                    "file_size" => $_FILES["image"]["size"],
                    "file_type" => $tipe_file,
                ];
                // proses simpan data
                $model_galeri = new Galeri();
                $result = $model_galeri->update_image($params_galeri_id, $form_data, $current_users_id);
                $data = [
                    'data' => $result
                ];
                header('Content-Type: application/json');
                http_response_code(200);
                echo json_encode($data);
                exit();
            }
            else {
                $data = [
                    'message' => 'Maaf, terjadi kesalahan saat mengupload file.'
                ];
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode($data);
                exit();
            }
        } catch (Exception $e) {
            $data = [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode($data);
            exit();
        }
    }

    public function delete_menghapus_data($param_galeri_id, $current_users_id)
    {

        $model_galeri = new Galeri();

        // cek params users_id yang akan dihapus datanya
        $cek_id = $model_galeri->findIdAndUsersId($param_galeri_id, $current_users_id);
        if ($cek_id == false) {
            http_response_code(404);
            echo json_encode(['message' => "Data $param_galeri_id tidak ditemukan"]);
            exit();
        }

        // Hapus file lama jika ada
        $oldFilePath = $cek_id['file'];
        if ($oldFilePath != null) {
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        $result = $model_galeri->menghapus_data($param_galeri_id);
        if ($result === false) {
            http_response_code(404);
            echo json_encode(['message' => 'Proses menghapus data gagal']);
            exit();
        }

        http_response_code(200);
        echo "";
    }
}