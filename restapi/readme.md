# Persiapan awal

```sql
create database proyek_galeri;
use proyek_galeri;
create table users
(
    id         int auto_increment primary key,
    nama       varchar(255)                  not null,
    no_telpon  varchar(20)                   not null,
    email      varchar(255)                  not null,
    password   varchar(255)                  not null,
    level      enum ('super admin', 'admin') not null,
    tgl_buat   datetime                      null,
    tgl_update datetime                      null,
    constraint email
        unique (email),
    constraint nama
        unique (nama)
);

create table galeri
(
    id         int auto_increment
        primary key,
    nama       varchar(255) not null,
    file       varchar(255) not null,
    file_name  varchar(255) not null,
    file_type  varchar(255) null,
    file_size  int          null,
    tgl_buat   datetime     not null,
    tgl_update datetime     not null,
    users_id   int          not null,
    constraint galeri_ibfk_1
        foreign key (users_id) references users (id),
    constraint fk_gallery_user_id
        foreign key (users_id) references users (id)
);

INSERT INTO proyek_galeri.users 
    (id, nama, no_telpon, email, password, level, tgl_buat, tgl_update) 
    VALUES 
    (1, 'super admin', '6289676041493', 'superadmin@gmail.com', '$2y$10$FYwzWwGBKYKplWgt8smt8eMhPrFrUJedA5zhu1BbPvCHWN4J8GzdO', 'super admin', '2024-01-01 00:00:00', '2024-01-01 00:00:00');

```