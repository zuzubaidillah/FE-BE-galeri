<?php

namespace Controllers;
require_once __DIR__ . "/../../model/Users.php";
require_once __DIR__ . "/../../config/TokenJwt.php";

use Config\TokenJwt;
use Model\Users;

class UsersController
{
    public function index_ambil_data() {
        $user = new Users();
        $result = $user->allData();
        echo json_encode(['data' => $result]);
    }

    public function show_detail_data($users_id) {
        $user = new Users();
        $result = $user->findId($users_id);
        if ($result === false) {
            http_response_code(404);
            echo json_encode(['message' => 'Data tidak ditemukan']);
            exit();
        }
        echo json_encode(['data' => $result]);
    }
}