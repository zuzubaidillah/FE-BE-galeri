<?php

namespace Controllers;
require_once __DIR__ . "/../../model/Users.php";
require_once __DIR__ . "/../../config/TokenJwt.php";

use Config\TokenJwt;
use Model\Users;

class GaleriController
{
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
        $result = $user->findId($verifikasi_token['users_id']);
        unset($result['password']);
        echo json_encode(['data' => $result]);
    }
}