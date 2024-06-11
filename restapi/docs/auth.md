# Auth API Spec (api spesifikasi)

## Persiapan data

- struktur table: **users**

| kolom      | type     |                                                                                           |
|:-----------|:---------|:------------------------------------------------------------------------------------------|
| id         | int      | AI, not null                                                                              |
| nama       | varchar  | not null, uniqe                                                                           |
| no_telpon  | varchar  | not null                                                                                  |
| email      | varchar  | not null, uniqe                                                                           |
| password   | varchar  | nilai yang disimpan harus berbentuk enkripsi,<br/> tidak boleh menggunakan nilai **ASLI** |
| level      | enum     | [super admin, admin]                                                                      |
| tgl_buat   | datetime | null                                                                                      |
| tgl_update | datetime | null                                                                                      |

- buat data untuk level super admin di table **users**

| id         | nama        | no_telpon           | email                | password | level       | tgl_buat | tgl_update |
|:-----------|:------------|:--------------------|----------------------|----------|-------------|:---------|------------|
| 1          | super admin | 6289676041493       | superadmin@gmail.com | ssss     | super admin | *null*   | *null*     |

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
  "email": "ronaldo@gmail.com", // isRequired
  "password": "ronaldo" // isRequired
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
  "message": "SQLSTATE[HY000] [1049] Unknown database 'nama-database-salah'"
}
```

## Lupa Password Reset Password API spesifikasi

Endpoint :  POST /smkti/FE-BE-galeri/restapi/api/auth/login/forgot-password

Bussiness Logic:
- validasi request dari client
- verifikasi email
- membuat password default **galeri2020**
- response data

Request Body :

```json
{
  "email": "ronaldo@gmail.com" // isRequired
}
```

Response Body Success(200) :

```json
{
  "message": "Akun berhasil dilakukan Reset Password, Gunakan password galeri2020"
}
```

Response Body Error(400) : ketika request body tidak sesuai

```json
{
  "message": "Data tidak lengkap"
}
```

Response Body Error(400) : ketika salah memasukan email

```json
{
  "message": "maaf email tidak terdaftar"
}
```

Response Body Error(500) : jika ada salah kode php atau salah proses koneksi ke database

```json
{
  "message": "SQLSTATE[HY000] [1049] Unknown database 'nama-database-salah'"
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