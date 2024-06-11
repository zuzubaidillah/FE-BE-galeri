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

- response data galeri secara keseluruhan berdasarkan pengguna
- client request params filter_q
  - maka, saat mengambil data di table **galeri** tambahkan **where** pada kolom **nama** dan gunakan perintah **like**
- urutkan data berdasarkan **tgl_buat** desc

Request params :

```json
{
  "filter_q": "", // optional | akan mencari data galeri berdasarkan kolom [nama]
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
  "message": "SQLSTATE[HY000] [1049] Unknown database 'nama-database-salah'"
}
```

## Mengambil Galeri berdasarkan id API spesifikasi

Endpoint :  GET /smkti/FE-BE-galeri/restapi/api/galeri/{{galeri_id}}

Bussiness Logic:

- cek request client, galeri_id
- cari data berdasarkan table **galeri** kolom **id**
- response data users berdasarkan params **galeri_id**

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

Response Body Error(404) : jika **galeri_id** yang dikirim tidak ditemukan pada **table galeri**

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

## Membuat Galeri Baru API spesifikasi

Endpoint :  POST /smkti/FE-BE-galeri/restapi/api/galeri

Bussiness Logic:

- validasi request
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
  "message": "File harus berupa: jpg, jpeg dan png"
}
```

Response Body Error(500) : jika ada salah kode php atau salah proses koneksi ke database

```json
{
  "message": "SQLSTATE[HY000] [1049] Unknown database 'nama-database-salah'"
}
```

## Merubah data Galeri API spesifikasi

Endpoint :  PUT /smkti/FE-BE-galeri/restapi/api/galeri/{{galeri_id}}

Bussiness Logic:

- cek request dari client
- cari data berdasarkan param **galeri_id** pada table **galeri** kolom **id**
- cek nama yang sama, tambahkan logika tidak sama dengan **galeri_id**
- update data
- response data yang baru saja di update

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
  "message": "Data 1 tidak ditemukan"
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
  "message": "SQLSTATE[HY000] [1049] Unknown database 'nama-database-salah'"
}
```

## Merubah data file pada Galeri API spesifikasi

Endpoint :  POST /smkti/FE-BE-galeri/restapi/api/galeri/{{galeri_id}}/file

Bussiness Logic:

- cek request dari client
- cari data berdasarkan param **galeri_id** table **galeri** kolom **id**
- lakukan hapus file yang lama dan simpan file baru kedalam folder yang ditentukan
- update data
- response data yang baru saja di update

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
  "message": "Data 1 tidak ditemukan"
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
  "message": "SQLSTATE[HY000] [1049] Unknown database 'nama-database-salah'"
}
```

## Menghapus data Galeri API spesifikasi

Endpoint :  DELETE /smkti/FE-BE-galeri/restapi/api/galeri/{{galeri_id}}

Bussiness Logic:

- cek request dari client
- cari data **galeri_id** berdasarkan table **galeri** kolom **id**
- hapus file yang ada di dalam folder galeri
- hapus data pada table **galeri**
- response empty

Request Body : -

Response Body Success(200) : ketika request body sesuai *empty response*

```json
```

Response Body Error(404) : ketika galeri_id tidak ditemukan

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