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

## Mengambil data User API spesifikasi

Endpoint :  GET /smkti/FE-BE-galeri/restapi/api/users

Bussiness Logic:

- response data users secara keseluruhan
- urutkan data berdasarkan **tgl_buat** **desc**

Request params :

```text
filter_q: "" // optional | akan mencari data users berdasarkan kolom [nama, email, no_telpon]
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
      "level": "super admin"
    },
    {
      "id": 2,
      "nama": "Messi",
      "no_telpon": "6289676041493",
      "email": "messi@gmail.com",
      "level": "admin"
    },
  ]
}
```

Response Body Error(500) : jika ada salah kode php atau salah proses koneksi ke database

```json
{
  "message": "SQLSTATE[HY000] [1049] Unknown database 'nama-database-salah'"
}
```

## Mengambil User berdasarkan id API spesifikasi

Endpoint :  GET /smkti/FE-BE-galeri/restapi/api/users/{{user_id}}

Bussiness Logic:

- cek request client
- cari data user_id berdasarkan table **users** kolom **id**
- response data **users** berdasarkan params **user_id**

Response Body Success(200) : ketika request body sesuai

```json
{
  "message": "Berhasil",
  "data": {
    "id": 1,
    "nama": "Ronaldo",
    "no_telpon": "6289676041493",
    "email": "ronaldo@gmail.com",
    "level": "super admin"
  }
}
```

Response Body Error(404) : jika user_id yang dikirim tidak ditemukan

```json
{
  "message": "Data 1 tidak ditemukan"
}
```

Response Body Error(500) : jika ada salah kode php atau salah proses koneksi ke database

```json
{
  "message": "SQLSTATE[HY000] [1049] Unknown database 'nama-database-salah'"
}
```

## Membuat User Baru API spesifikasi

Endpoint :  POST /smkti/FE-BE-galeri/restapi/api/users

Bussiness Logic:

- cek level yang boleh melakukan buat users adalah level super admin
- cek nama yang sama
- cek email yang sama
- sebelum menyimpan no telpon, pastikan diformat dengan 628 karena akan dimanfaatkan untuk kirim pesan whatsapp
- password ubah ke enkripsi sebelum disimpan
- simpan data
- response data yang baru saja di simpan

Request Body :

```json
{
  "nama": "Ronaldo", // isRequired | string
  "no_telpon": "089676041493", // isRequired | string
  "email": "ronaldo@gmail.com", // isRequired | string
  "password": "ronaldo", // isRequired | string
  "level": "admin" // isRequired | string
}
```

Response Body Success(200) : ketika request body sesuai

```json
{
  "message": "Berhasil",
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
  "message": "SQLSTATE[HY000] [1049] Unknown database 'nama-database-salah'"
}
```

## Merubah data User API spesifikasi

Endpoint :  PUT /smkti/FE-BE-galeri/restapi/api/users/{{user_id}}

Bussiness Logic:

- cek request dari client
- cari data berdasarkan table **users** kolom **id**
- cek nama yang sama, tambahkan logika tidak sama dengan user_id
- cek email yang sama, tambahkan logika tidak sama dengan user_id
- sebelum menyimpan no telpon, pastikan diformat dengan 628 karena akan dimanfaatkan untuk kirim pesan whatsapp
- password bersifat opsional, password jika dikirim maka diubah ke enkripsi sebelum disimpan
- update data
- response data yang baru saja di update

Request Body :

```json
{
  "nama": "Ronaldo", // isRequired | string
  "email": "ronaldo@gmail.com", // isRequired | string
  "password": "ronaldo", // optional | string
  "level": "admin" // isRequired | string
}
```

Response Body Success(200) : ketika request body sesuai

```json
{
  "message": "Berhasil",
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

Response Body Error(404) : ketika user_id tidak ditemukan

```json
{
  "message": "Data 1 tidak ditemukan"
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
  "message": "SQLSTATE[HY000] [1049] Unknown database 'nama-database-salah'"
}
```

## Menghapus data User API spesifikasi

Endpoint :  DELETE /smkti/FE-BE-galeri/restapi/api/users/{{user_id}}

Bussiness Logic:

- cek request dari client
- cari data berdasarkan table **users** kolom **id**
- response empty

Request Body : -

Response Body Success(200) : ketika request body sesuai *empty response*

```json
```

Response Body Error(404) : ketika user_id tidak ditemukan

```json
{
  "message": "Data 1 tidak ditemukan"
}
```

Response Body Error(500) : jika ada salah kode php atau salah proses koneksi ke database

```json
{
  "message": "SQLSTATE[HY000] [1049] Unknown database 'nama-database-salah'"
}
```