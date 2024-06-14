<?php
//file Routes.php digunakan untuk list sebuah endpoint
require_once __DIR__ . '/../config/Route.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/UsersController.php';
require_once __DIR__ . '/../config/TokenJwt.php';
require_once __DIR__ . '/../model/Users.php';

use Config\Route;
use Config\TokenJwt;
use Controllers\AuthController;
use Controllers\UsersController;
use Model\Users;

$base_url = "/smkti/FE-BE-galeri/restapi";

/**
 * API AUTH / USER
 * */
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

    // jika tidak mohammad zuz Authorization
    // jika tidak mohammad zuz Authorization
    if (!$jwt) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Akses ditolak. Token tidak ditemukan.']);
        exit();
    }

    //proses cek token
    try {
        $token_jwt = new TokenJwt();
        $verifikasi_token = $token_jwt->verify($jwt);

        $model_user = new Users();
        $result = $model_user->findId($verifikasi_token['users_id']);
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

/**
 * API USERS
 * */
Route::get($base_url . "/api/users", function (){
    // ambil bearer Token yang di request client
    $headers = getallheaders();
    $jwt = null;

    // jika ada array dengan key Authorization
    if (isset($headers['Authorization'])) {
        $bearer = explode(' ', $headers['Authorization']);
        $jwt = $bearer[sizeof($bearer) - 1] ?? null;
    }

    // jika tidak mohammad zuz Authorization
    if (!$jwt) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Akses ditolak. Token tidak ditemukan.']);
        exit();
    }

    //proses cek token
    try {
        $token_jwt = new TokenJwt();
        $verifikasi_token = $token_jwt->verify($jwt);

        $model_user = new Users();
        $result = $model_user->findId($verifikasi_token['users_id']);
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

    $controller = new UsersController();
    $controller->index_ambil_data();
});

Route::get($base_url . "/api/users/{users_id}", function ($model_users_id){
    // ambil bearer Token yang di request client
    $headers = getallheaders();
    $jwt = null;

    // jika ada array dengan key Authorization
    if (isset($headers['Authorization'])) {
        $bearer = explode(' ', $headers['Authorization']);
        $jwt = $bearer[sizeof($bearer) - 1] ?? null;
    }

    // jika tidak mohammad zuz Authorization
    if (!$jwt) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Akses ditolak. Token tidak ditemukan.']);
        exit();
    }

    //proses cek token
    try {
        $token_jwt = new TokenJwt();
        $verifikasi_token = $token_jwt->verify($jwt);

        $model_user = new Users();
        $result = $model_user->findId($verifikasi_token['users_id']);
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

    $controller = new UsersController();
    $controller->show_detail_data($model_users_id);
});

Route::post($base_url . "/api/users", function (){
    // ambil bearer Token yang di request client
    $headers = getallheaders();
    $jwt = null;

    // jika ada array dengan key Authorization
    if (isset($headers['Authorization'])) {
        $bearer = explode(' ', $headers['Authorization']);
        $jwt = $bearer[sizeof($bearer) - 1] ?? null;
    }

    // jika tidak mohammad zuz Authorization
    if (!$jwt) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Akses ditolak. Token tidak ditemukan.']);
        exit();
    }

    //proses cek token
    try {
        $token_jwt = new TokenJwt();
        $verifikasi_token = $token_jwt->verify($jwt);

        $model_user = new Users();
        $result = $model_user->findId($verifikasi_token['users_id']);
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

    // harus level super admin
    if ($result['level'] !== 'super admin') {
        http_response_code(403); // not access
        echo json_encode(['message' => 'Akses ditolak. Anda bukan level super admin.']);
        exit();
    }

    $controller = new UsersController();
    $controller->store_buat_data_baru($result);
});

Route::put($base_url . "/api/users/{users_id}", function ($param_users_id){
    // ambil bearer Token yang di request client
    $headers = getallheaders();
    $jwt = null;

    // jika ada array dengan key Authorization
    if (isset($headers['Authorization'])) {
        $bearer = explode(' ', $headers['Authorization']);
        $jwt = $bearer[sizeof($bearer) - 1] ?? null;
    }

    // jika tidak mohammad zuz Authorization
    if (!$jwt) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Akses ditolak. Token tidak ditemukan.']);
        exit();
    }

    //proses cek token
    try {
        $token_jwt = new TokenJwt();
        $verifikasi_token = $token_jwt->verify($jwt);

        $model_user = new Users();
        $current_user = $model_user->findId($verifikasi_token['users_id']);
        if (!$current_user) {
            http_response_code(401);
            echo json_encode(['message' => 'users tidak ditemukan']);
            exit();
        }
    } catch (Exception $e) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Token tidak valid: ' . $e->getMessage()]);
        exit();
    }

    // harus level super admin
    if ($current_user['level'] !== 'super admin') {
        http_response_code(403); // not access
        echo json_encode(['message' => 'Akses ditolak. Anda bukan level super admin.']);
        exit();
    }

    $controller = new UsersController();
    $controller->update_merubah_data($param_users_id, $current_user);
});

Route::delete($base_url . "/api/users/{users_id}", function ($param_users_id){
    // ambil bearer Token yang di request client
    $headers = getallheaders();
    $jwt = null;

    // jika ada array dengan key Authorization
    if (isset($headers['Authorization'])) {
        $bearer = explode(' ', $headers['Authorization']);
        $jwt = $bearer[sizeof($bearer) - 1] ?? null;
    }

    // jika tidak mohammad zuz Authorization
    if (!$jwt) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Akses ditolak. Token tidak ditemukan.']);
        exit();
    }

    //proses cek token
    try {
        $token_jwt = new TokenJwt();
        $verifikasi_token = $token_jwt->verify($jwt);

        $model_user = new Users();
        $current_user = $model_user->findId($verifikasi_token['users_id']);
        if (!$current_user) {
            http_response_code(401);
            echo json_encode(['message' => 'users tidak ditemukan']);
            exit();
        }
    } catch (Exception $e) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Token tidak valid: ' . $e->getMessage()]);
        exit();
    }

    // harus level super admin
    if ($current_user['level'] !== 'super admin') {
        http_response_code(403); // not access
        echo json_encode(['message' => 'Akses ditolak. Anda bukan level super admin.']);
        exit();
    }

    $controller = new UsersController();
    $controller->delete_menghapus_data($param_users_id, $current_user);
});

/**
 * API GALERI
 * */
Route::get($base_url . "/api/galeri", function (){
    // ambil bearer Token yang di request client
    $headers = getallheaders();
    $jwt = null;

    // jika ada array dengan key Authorization
    if (isset($headers['Authorization'])) {
        $bearer = explode(' ', $headers['Authorization']);
        $jwt = $bearer[sizeof($bearer) - 1] ?? null;
    }

    // jika tidak mohammad zuz Authorization
    if (!$jwt) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Akses ditolak. Token tidak ditemukan.']);
        exit();
    }

    //proses cek token
    try {
        $token_jwt = new TokenJwt();
        $verifikasi_token = $token_jwt->verify($jwt);

        $model_user = new Users();
        $result = $model_user->findId($verifikasi_token['users_id']);
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
