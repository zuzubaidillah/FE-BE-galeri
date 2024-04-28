<?php
namespace Config;
require_once __DIR__ . "/JWT/JWT.php";
require_once __DIR__ . "/JWT/Key.php";
require_once __DIR__ . "/JWT/ExpiredException.php";

use Firebase\JWT\{JWT,Key, ExpiredException};

class TokenJwt
{
    private $key = "f5d70cba0e7a80c96bf774b863ac54ecd2c20ae56fee32e627717cb09799601e";
    private $domain = "http://yourdomain.com";

    public function __construct()
    {
    }

    public function verify($jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
            return [
                "user_id" => $decoded->user_id
            ];
        } catch (ExpiredException $e) {
            http_response_code(401);
            echo json_encode([
                "message" => "Token telah kedaluwarsa"
            ]);
            exit();
        } catch (Exception $e) {
            // Kesalahan lainnya
            http_response_code(401);
            echo json_encode(['message' => 'Token tidak valid']);
            exit();
        }
    }

    public function create($id)
    {
        $payload = [
            "iss" => $this->domain, // Penerbit
            "aud" => $this->domain, // Audience
            "iat" => time(), // Waktu di mana token diterbitkan
            "exp" => time() + 24*60 * 60, //24 * 60 * 60, // Expire dalam 24 jam
            "user_id" => $id // Menambahkan user_id ke payload
        ];
        return JWT::encode($payload, $this->key, 'HS256');
    }
}

?>
