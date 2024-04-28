# Auth API Spec (api spesifikasi)

## Register User API spesifikasi

- struktur table: **users**

| kolom      | type     |    |
|:-----------|:---------|:---|
| id         | int      | AI |
| nama       | varchar  |    |
| file_name  | varchar  |    |
| file_type  | False    |    |
| file_size  | int      |    |
| tgl_buat   | datetime |    |
| tgl_update | datetime |    |

## Register User API spesifikasi

Endpoint :  POST /smkti/FE-BE-galeri/restapi/api/auth/registrasi

Bussiness Logic:
- cek email yang sama
- password jadi enkripsi sebelum disimpan
- simpan data
- response data yang baru saja di simpan

Request Body :

```json
{
  "name": "Ronaldo", // isRequired
  "email": "ronaldo@gmail.com", // isRequired
  "password": "ronaldo" // isRequired
}
```

Response Body Success(200) : ketika request body sesuai

```json
{
  "message": "Registrasi berhasil",
  "data": {
    "id": 1,
    "name": "Ronaldo",
    "email": "ronaldo@gmail.com",
    "file": null,
    "created_at": "2024-01-17 13:58:07",
    "updated_at": null,
    "deleted_at": null
  }
}
```

Response Body Error(400) : ketika request tidak sesuai

```json
{
  "message": "Data tidak lengkap harus diisi"
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
- response data sesuai verifikasi email dan password, beserta token yang telah dibuat

Request Body :

```json
{
  "email": "ronaldo@gmail.com", // isRequired
  "password": "ronaldo" // isRequired
}
```

Response Body Success(200) :

```json
{
  "data": {
    "id": 1,
    "name": "Ronaldo",
    "email": "ronaldo@gmail.com",
    "file": null,
    "created_at": "2024-01-17 13:58:07",
    "updated_at": null,
    "deleted_at": null
  },
  "message": "Login berhasil",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8veW91cmRvbWFpbi5jb20iLCJhdWQiOiJodHRwOi8veW91cmRvbWFpbi5jb20iLCJpYXQiOjE3MDU1MDEzNjMsImV4cCI6MTcwNTUwMTQ4MywidXNlcl9pZCI6MX0.khWRvPvQJhgpRuBW0KYAaScGgN-uoRly8_CnPL-WgEE"
}
```

Response Body Error(400) : ketika request body tidak sesuai

```json
{
  "message": "Data tidak lengkap harus diisi"
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
    "name": "Ronaldo",
    "email": "ronaldo@gmail.com",
    "file": null,
    "created_at": "2024-01-17 13:58:07",
    "updated_at": null,
    "deleted_at": null
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