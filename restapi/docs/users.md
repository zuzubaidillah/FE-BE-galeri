# Auth API Spec (api spesifikasi)

## Persiapan data

- struktur table: **users**

| kolom      | type     |                                                                                           |
|:-----------|:---------|:------------------------------------------------------------------------------------------|
| id         | int      | AI, not null                                                                              |
| nama       | varchar  | not null                                                                                  |
| no_telpon  | varchar  | not null                                                                                  |
| email      | varchar  | not null                                                                                  |
| password   | varchar  | nilai yang disimpan harus berbentuk enkripsi,<br/> tidak boleh menggunakan nilai **ASLI** |
| level      | enum     | [super admin, admin]                                                                      |
| tgl_buat   | datetime | null                                                                                      |
| tgl_update | datetime | null                                                                                      |

## Mengambil data User API spesifikasi

Endpoint :  GET /smkti/FE-BE-galeri/restapi/api/users

Bussiness Logic:

- response data users secara keseluruhan
- urutkan data berdasarkan *tgl_buat* desc

Request params :

```json
{
  "filter_q": ""
  // optional | akan mencari data users berdasarkan kolom [nama, email, no_telpon]
}
```

Response Body Success(200) : ketika request body sesuai

```json
{
  "message": "Berhasil",
  "data": [
    {
      "id": 1,
      "nama": "Ronaldo",
      "no_telpon": "6289676041493",
      "email": "ronaldo@gmail.com",
      "level": "admin"
    }
  ]
}
```

Response Body Error(500) : jika ada salah kode php atau salah proses koneksi ke database

```json
{
  "message": "SQLSTATE[HY000] [1049] Unknown database 'bookshelf-acak'"
}
```

## Mengambil detail User API spesifikasi

Endpoint :  GET /smkti/FE-BE-galeri/restapi/api/users/{{users_id}}

Bussiness Logic:

- cek request client
- cari data berdasarkan table *users* kolom *id*
- response data users berdasarkan params *users_id*

Response Body Success(200) : ketika request body sesuai

```json
{
  "message": "Berhasil",
  "data": {
    "id": 1,
    "nama": "Ronaldo",
    "no_telpon": "6289676041493",
    "email": "ronaldo@gmail.com",
    "level": "admin"
  }
}
```

Response Body Error(404) : jika users_id yang dikirim tidak ditemukan

```json
{
  "message": "Data tidak ditemukan"
}
```

Response Body Error(500) : jika ada salah kode php atau salah proses koneksi ke database

```json
{
  "message": "SQLSTATE[HY000] [1049] Unknown database 'bookshelf-acak'"
}
```

## Membuat User Baru API spesifikasi

Endpoint :  POST /smkti/FE-BE-galeri/restapi/api/users

Bussiness Logic:

- cek nama yang sama
- cek email yang sama
- sebelum menyimpan no telpon, pastikan diformat dengan +628 karena akan dimanfaatkan untuk whatsapp
- password jadi enkripsi sebelum disimpan
- simpan data
- response data yang baru saja di simpan

Request Body :

```json
{
  "nama": "Ronaldo",
  // isRequired | string
  "email": "ronaldo@gmail.com",
  // isRequired | string
  "password": "ronaldo",
  // isRequired | string
  "level": "admin"
  // isRequired | string
}
```

Response Body Success(200) : ketika request body sesuai

```json
{
  "message": "Registrasi berhasil",
  "data": {
    "id": 1,
    "nama": "Ronaldo",
    "email": "ronaldo@gmail.com",
    "level": "admin",
    "tgl_buat": "2024-01-01 00:00:00",
    "tgl_update": "2024-01-01 00:00:00"
  }
}
```

Response Body Error(400) : ketika request tidak sesuai

```json
{
  "message": "Data tidak lengkap"
}
```

Response Body Error(409) : ketika nama sudah ada

```json
{
  "message": "Nama sudah digunakan"
}
```

Response Body Error(409) : ketika email sudah ada

```json
{
  "message": "Email sudah digunakan"
}
```

Response Body Error(500) : jika ada salah kode php atau salah proses koneksi ke database

```json
{
  "message": "SQLSTATE[HY000] [1049] Unknown database 'bookshelf-acak'"
}
```

## Login User API spesifikasi

Endpoint :  POST /smkti/FE-BE-galeri/restapi/api/auth/login

Bussiness Logic:

- validasi request dari client
- verifikasi email
- verifikasi password
- membuat token menggunakan JWT (JSON Web Token)
- response data users dan tambahkan key token (agar digunakan untuk authentikasi)

Request Body :

```json
{
  "email": "ronaldo@gmail.com",
  // isRequired
  "password": "ronaldo"
  // isRequired
}
```

Response Body Success(200) :

```json
{
  "data": {
    "id": 1,
    "nama": "Ronaldo",
    "email": "ronaldo@gmail.com",
    "level": "admin",
    "tgl_buat": "2024-01-01 00:00:00",
    "tgl_update": "2024-01-01 00:00:00"
  },
  "message": "Login berhasil",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8veW91cmRvbWFpbi5jb20iLCJhdWQiOiJodHRwOi8veW91cmRvbWFpbi5jb20iLCJpYXQiOjE3MDU1MDEzNjMsImV4cCI6MTcwNTUwMTQ4MywidXNlcl9pZCI6MX0.khWRvPvQJhgpRuBW0KYAaScGgN-uoRly8_CnPL-WgEE"
}
```

Response Body Error(400) : ketika request body tidak sesuai

```json
{
  "message": "Data tidak lengkap"
}
```

Response Body Error(400) : ketika salah memasukan email / password

```json
{
  "message": "login gagal, cek email dan password"
}
```

Response Body Error(500) : jika ada salah kode php atau salah proses koneksi ke database

```json
{
  "message": "SQLSTATE[HY000] [1049] Unknown database 'bookshelf-acak'"
}
```

## Get Current User API

Endpoint : GET /smkti/FE-BE-galeri/restapi/api/auth/current

Headers :

- Authorization : Bearer {{token}}

Response Body Success(200): ketika token sesuai

```json
{
  "data": {
    "id": 1,
    "nama": "Ronaldo",
    "email": "ronaldo@gmail.com",
    "level": "admin",
    "tgl_buat": "2024-01-01 00:00:00",
    "tgl_update": "2024-01-01 00:00:00"
  }
}
```

Response Body Error(400):

```json
{
  "message": "Token telah kedaluwarsa"
}
```

```json
{
  "message": "Token tidak valid"
}
```

```json
{
  "message": "Akses ditolak. Token tidak ditemukan."
}
```

```json
{
  "message": "users tidak ditemukan"
}
```

```json
{
  "message": "Token tidak valid: ...."
}
```