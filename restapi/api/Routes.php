<?php
//file Routes.php digunakan untuk list sebuah endpoint
require_once __DIR__ . '/../config/Route.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/../config/TokenJwt.php';
require_once __DIR__ . '/../model/Users.php';

use Config\Route;
use Config\TokenJwt;
use Controllers\AuthController;
use Model\Users;

$base_url = "/smkti/restapi-bookshelf-level2-action-video";

Route::post($base_url . "/api/registrasi", function () {
    echo json_encode([
        "message" => "ini registrasi"
    ]);
});

/**
 * API AUTH / USER
 * */
Route::post($base_url . "/api/auth/registrasi", function (){
    $controller = new AuthController();
    $controller->registrasi();
});
Route::post($base_url . "/api/auth/login", function (){
    $controller = new AuthController();
    $controller->login();
});
Route::get($base_url . "/api/auth/current", function (){
    // ambil bearer Token yang di request client
    $headers = getallheaders();
    $jwt = null;

    // jika ada array dengan key Authorization
    if (isset($headers['Authorization'])) {
        $bearer = explode(' ', $headers['Authorization']);
        $jwt = $bearer[sizeof($bearer) - 1] ?? null;
    }

    // jika tidak ditemukan key Authorization
    if (!$jwt) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Akses ditolak. Token tidak ditemukan.']);
        exit();
    }

    //proses cek token
    try {
        $token_jwt = new TokenJwt();
        $verifikasi_token = $token_jwt->verify($jwt);

        $user = new Users();
        $result = $user->findId($verifikasi_token['user_id']);
        if (!$result) {
            http_response_code(401);
            echo json_encode(['message' => 'users tidak ditemukan']);
            exit();
        }
    } catch (Exception $e) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Token tidak valid: ' . $e->getMessage()]);
        exit();
    }
    
    $controller = new AuthController();
    $controller->current();
});


// Add more routes here
Route::run();
