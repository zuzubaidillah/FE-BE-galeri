<?php

namespace Controllers;
require_once __DIR__ . "/../../model/Users.php";
require_once __DIR__ . "/../../config/TokenJwt.php";

use Config\TokenJwt;
use Model\Users;

class AuthController
{
    public function registrasi()
    {
        // menerima request dari client content-type: JSON
        $request = json_decode(file_get_contents('php://input'), true);

        // validasi request client
        if (empty($request['name']) || empty($request['email']) || empty($request['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Data tidak lengkap harus diisi']);
            exit();
        }

        // validasi email sama
        $model_users = new Users();
        $validasi_email = $model_users->findEmail($request['email']);
        if ($validasi_email != false) {
            http_response_code(409);
            echo json_encode(['message' => 'Email sudah digunakan']);
            exit();
        }

        // enkripsi password
        $password_hased = password_hash($request['password'], PASSWORD_DEFAULT);
        $form_data = [
            "name" => $request['name'],
            "email" => $request['email'],
            "password" => $password_hased,
        ];

        // simpan data
        $result = $model_users->registrasi($form_data);

        if ($result) {
            // key password tidak perlu di response
            unset($result['password']);

            //response data
            http_response_code(201); // Created
            echo json_encode([
                'message' => 'Registrasi berhasil',
                'data' => $result
            ]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Registrasi gagal, terjadi kesalahan pada database saat proses registrasi']);
        }
    }

    public function login()
    {
        // menerima request dari client content-type: JSON
        $request = json_decode(file_get_contents('php://input'), true);

        // validasi request
        if (empty($request['email']) || empty($request['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Data tidak lengkap harus diisi']);
            exit();
        }

        // verifikasi email
        $model_users = new Users();
        $verifikasi_email = $model_users->findEmail($request['email']);
        if ($verifikasi_email === false) {
            http_response_code(400);
            echo json_encode(['message' => 'login gagal, cek email dan password']);
            exit();
        }

        // verifikasi password
        $verifikasi_password = password_verify($request['password'], $verifikasi_email['password']);
        if ($verifikasi_password === false) {
            // response gagal
            http_response_code(400);
            echo json_encode(['message' => 'login gagal, cek email dan password']);
            exit();
        }

        // membuat token library JWT (JSON Web Token)
        $library_token = new TokenJwt();
        $token_baru = $library_token->create($verifikasi_email['id']);

        // hapus key password
        unset($verifikasi_email['password']);

        // response data dan token
        http_response_code(200);
        echo json_encode([
            'data' => $verifikasi_email,
            'message' => 'Login Berhasil',
            'token' => $token_baru
        ]);
    }

    public function current() {
        // Mendapatkan token dari header
        $headers = getallheaders();
        $jwt = null;

        // parsing token
        if (isset($headers['Authorization'])) {
            $bearer = explode(' ', $headers['Authorization']);
            $index_token = (sizeof($bearer)-1);
            $jwt = $bearer[$index_token] ?? null;
        }

        // response token tidak ditemukan
        if (!$jwt) {
            echo json_encode(['message' => 'Akses ditolak. Token tidak ditemukan.']);
            http_response_code(401); // Unauthorized
            exit();
        }

        // proses decrip
        $token_jwt = new TokenJwt();
        $verifikasi_token = $token_jwt->verify($jwt);

        // setiap token kita harus verifikasi ke table users by users_id
        $user = new Users();
        $result = $user->findId($verifikasi_token['user_id']);
        unset($result['password']);
        echo json_encode(['data' => $result]);
    }
}