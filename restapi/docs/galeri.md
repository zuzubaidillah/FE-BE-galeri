# Auth API Spec (api spesifikasi)

## Persiapan data

- struktur table: **galeri**

| kolom      | type     |          |
|:-----------|:---------|:---------|
| id         | int      | AI       |
| nama       | varchar  | not null |
| file_name  | varchar  | not null |
| file_type  | False    | null     |
| file_size  | int      | null     |
| tgl_buat   | datetime | not null |
| tgl_update | datetime | not null |
| users_id   | int      | not null |

## Mengambil data galeri API spesifikasi

Endpoint :  GET /smkti/FE-BE-galeri/restapi/api/galeri

Bussiness Logic:

- response data galeri secara keseluruhan
- client request params filter_q
  - maka, saat mengambil data di table *galeri* tambahkan *where* pada kolom *nama* dan gunakan perintah *like*
- client request params filter_users_id
  - maka, saat mengambil data di table *galeri* tambahkan *where* pada kolom *users_id* dan gunakan perintah *=*
- urutkan data berdasarkan *tgl_buat* desc

Request params :

```json
{
  "filter_q": "", // optional | akan mencari data users berdasarkan kolom [nama]
  "filter_users_id": 0 // optional
}
```

Response Body Success(200) : ketika request body sesuai

```json
{
  "message": "Registrasi berhasil",
  "data": [
    {
      "id": 1,
      "name": "Ronaldo",
      "file": "/galeri/3923sas-s2323.png",
      "file_type": "png",
      "file_size": 2000,
      "tgl_buat": "2024-01-17 13:58:07",
      "tgl_update": "2024-01-17 13:58:07",
      "users_id": 1,
      "users_name": "Ronaldo",
      "users_level": "super admin"
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

## Mengambil Galeri berdasarkan id API spesifikasi

Endpoint :  GET /smkti/FE-BE-galeri/restapi/api/galeri/{{galeri_id}}

Bussiness Logic:

- cek request client
- cari data berdasarkan table *galeri* kolom *id*
- response data users berdasarkan params *galeri_id*

Response Body Success(200) : ketika request body sesuai

```json
{
  "message": "Berhasil",
  "data": {
    "id": 1,
    "name": "Ronaldo",
    "file": "/galeri/3923sas-s2323.png",
    "file_type": "png",
    "file_size": 2000,
    "tgl_buat": "2024-01-17 13:58:07",
    "tgl_update": "2024-01-17 13:58:07",
    "users_id": 1,
    "users_name": "Ronaldo",
    "users_level": "super admin"
  }
}
```

Response Body Error(404) : jika galeri_id yang dikirim tidak ditemukan

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

## Membuat Galeri Baru API spesifikasi

Endpoint :  POST /smkti/FE-BE-galeri/restapi/api/galeri

Bussiness Logic:

- cek nama yang sama
- cek request file harus tipe: [png, jpg, jpeg]
- simpan file kedalam folder galeri
- simpan data
- response data yang baru saja di simpan

Request Body form-data : gunakan (multipart-form-data)

```json
{
  "nama": "Ronaldo", // isRequired | string
  "file": (binary) // isRequired | string
}
```

Response Body Success(200) : ketika request body sesuai

```json
{
  "message": "Berhasil",
  "data": {
    "id": 1,
    "name": "Ronaldo",
    "file": "/galeri/3923sas-s2323.png",
    "file_type": "png",
    "file_size": 2000,
    "tgl_buat": "2024-01-17 13:58:07",
    "tgl_update": "2024-01-17 13:58:07",
    "users_id": 1,
    "users_name": "Ronaldo",
    "users_level": "super admin"
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

Response Body Error(400) : ketika file tidak sesuai kriteria

```json
{
  "message": "File harus berupa: jpg,jpeg dan png"
}
```

Response Body Error(500) : jika ada salah kode php atau salah proses koneksi ke database

```json
{
  "message": "SQLSTATE[HY000] [1049] Unknown database 'bookshelf-acak'"
}
```

## Merubah data Galeri API spesifikasi

Endpoint :  PUT /smkti/FE-BE-galeri/restapi/api/galerigaleri_id}}

Bussiness Logic:

- cek request dari client
- cari data berdasarkan table *galeri* kolom *id*
- cek nama yang sama, tambahkan logika tidak sama dengan galeri_id
- simpan data
- response data yang baru saja di simpan

Request Body :

```json
{
  "nama": "Ronaldo" // isRequired | string
}
```

Response Body Success(200) : ketika request body sesuai

```json
{
  "message": "Berhasil",
  "data": {
    "id": 1,
    "name": "Ronaldo",
    "file": "/galeri/3923sas-s2323.png",
    "file_type": "png",
    "file_size": 2000,
    "tgl_buat": "2024-01-17 13:58:07",
    "tgl_update": "2024-01-17 13:58:07",
    "users_id": 1,
    "users_name": "Ronaldo",
    "users_level": "super admin"
  }
}
```

Response Body Error(400) : ketika request tidak sesuai

```json
{
  "message": "Data tidak lengkap"
}
```

Response Body Error(404) : ketika galeri_id tidak ditemukan

```json
{
  "message": "Data tidak ditemukan"
}
```

Response Body Error(409) : ketika nama sudah ada

```json
{
  "message": "Nama sudah digunakan"
}
```

Response Body Error(500) : jika ada salah kode php atau salah proses koneksi ke database

```json
{
  "message": "SQLSTATE[HY000] [1049] Unknown database 'bookshelf-acak'"
}
```

## Merubah data file pada Galeri API spesifikasi

Endpoint :  POST /smkti/FE-BE-galeri/restapi/api/galeri/{{users_id}}/file

Bussiness Logic:

- cek request dari client
- cari data berdasarkan table *galeri* kolom *id*
- lakukan hapus file yang lama dan simpan file baru
- simpan data
- response data yang baru saja di simpan

Request Body : format-data (multipart-form-data)

```json
{
  "file": (binary) // isRequired | string
}
```

Response Body Success(200) : ketika request body sesuai

```json
{
  "message": "Berhasil",
  "data": {
    "id": 1,
    "name": "Ronaldo",
    "file": "/galeri/3923sas-s2323.png",
    "file_type": "png",
    "file_size": 2000,
    "tgl_buat": "2024-01-17 13:58:07",
    "tgl_update": "2024-01-17 13:58:07",
    "users_id": 1,
    "users_name": "Ronaldo",
    "users_level": "super admin"
  }
}
```

Response Body Error(400) : ketika request tidak sesuai

```json
{
  "message": "Data tidak lengkap"
}
```

Response Body Error(404) : ketika galeri_id tidak ditemukan

```json
{
  "message": "Data tidak ditemukan"
}
```

Response Body Error(400) : ketika file tidak sesuai kriteria

```json
{
  "message": "File harus berupa: jpg,jpeg dan png"
}
```

Response Body Error(500) : jika ada salah kode php atau salah proses koneksi ke database

```json
{
  "message": "SQLSTATE[HY000] [1049] Unknown database 'bookshelf-acak'"
}
```

## Menghapus data Galeri API spesifikasi

Endpoint :  DELETE /smkti/FE-BE-galeri/restapi/api/galeri/{{galeri_id}}

Bussiness Logic:

- cek request dari client
- cari data berdasarkan table *galeri* kolom *id*
- hapus file yang ada di dalam folder galeri
- response empty

Request Body : -

Response Body Success(200) : ketika request body sesuai *empty response*

```json
```

Response Body Error(404) : ketika galeri_id tidak ditemukan

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