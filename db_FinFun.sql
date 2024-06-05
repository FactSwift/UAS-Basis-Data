-- Made by MKB 2A

-- Create database
CREATE DATABASE FinFun;
USE FinFun;

-- Create tables
CREATE TABLE Pengguna (
  id_pengguna INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(255) NOT NULL,
  nomor_hp VARCHAR(255) NOT NULL UNIQUE,
  alamat_email VARCHAR(255) NOT NULL UNIQUE,
  saldo DECIMAL(10,2) NOT NULL DEFAULT 0,
  status_aktif BOOLEAN NOT NULL DEFAULT TRUE,
  virtual_account VARCHAR(255) NOT NULL
);

CREATE TABLE Merchant (
  id_merchant INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama_toko VARCHAR(255) NOT NULL,
  alamat_toko VARCHAR(255) NOT NULL,
  nomor_hp VARCHAR(255) NOT NULL UNIQUE,
  alamat_email VARCHAR(255) NOT NULL UNIQUE,
  saldo_akhir DECIMAL(10,2) NOT NULL DEFAULT 0,
  status_aktif BOOLEAN NOT NULL DEFAULT TRUE,
  id_toko_gipay VARCHAR(255) NOT NULL
);

CREATE TABLE Transaksi (
  id_transaksi INT PRIMARY KEY AUTO_INCREMENT,
  id_pengguna INT NOT NULL,
  id_merchant INT NOT NULL,
  jumlah_pembayaran DECIMAL(10,2) NOT NULL,
  tanggal_transaksi DATE NOT NULL,
  waktu_transaksi TIME NOT NULL,
  status_transaksi ENUM('Sukses', 'Gagal') NOT NULL DEFAULT 'Gagal',
  FOREIGN KEY (id_pengguna) REFERENCES Pengguna(id_pengguna),
  FOREIGN KEY (id_merchant) REFERENCES Merchant(id_merchant)
);

CREATE TABLE Penarikan_Dana (
  id_penarikan INT PRIMARY KEY AUTO_INCREMENT,
  id_merchant INT NOT NULL,
  nomor_rekening VARCHAR(255) NOT NULL,
  nama_bank VARCHAR(255) NOT NULL,
  jumlah_penarikan DECIMAL(10,2) NOT NULL,
  tanggal_penarikan DATE NOT NULL,
  waktu_penarikan TIME NOT NULL,
  status_penarikan ENUM('Menunggu', 'Diproses', 'Selesai') NOT NULL DEFAULT 'Menunggu',
  FOREIGN KEY (id_merchant) REFERENCES Merchant(id_merchant)
);

CREATE TABLE Statistik_Transaksi_Harian (
  id_statistik INT PRIMARY KEY AUTO_INCREMENT,
  tanggal DATE NOT NULL,
  id_merchant INT NOT NULL,
  total_transaksi INT NOT NULL,
  total_pembayaran DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (id_merchant) REFERENCES Merchant(id_merchant)
);

CREATE TABLE Statistik_Penarikan_Dana_Harian (
  id_statistik INT PRIMARY KEY AUTO_INCREMENT,
  tanggal DATE NOT NULL,
  id_merchant INT NOT NULL,
  total_penarikan INT NOT NULL,
  total_penarikan_dana DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (id_merchant) REFERENCES Merchant(id_merchant)
);

-- Create Admin
CREATE TABLE Admin (
  id_admin INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(255) NOT NULL
);

-- Create komisi Gi-Pay
CREATE TABLE GiPay_Commission (
  id_commission INT PRIMARY KEY AUTO_INCREMENT,
  rate DECIMAL(5,2) NOT NULL,
  effective_date DATE NOT NULL
);

-- Create Signup Log
CREATE TABLE Signup_Log (
  id_log INT PRIMARY KEY AUTO_INCREMENT,
  user_type ENUM('Pengguna', 'Merchant') NOT NULL,
  signup_date DATE NOT NULL
);

-- default rate untuk komisi
INSERT INTO GiPay_Commission (rate, effective_date) VALUES (0.5, CURDATE());

-- default admin account
INSERT INTO Admin (username, password, nama_lengkap) 
VALUES ('Shadow01', MD5('0987'), 'Kelompok Enam');

-- fix
ALTER TABLE Pengguna ADD COLUMN tanggal_daftar DATE DEFAULT CURRENT_DATE;
ALTER TABLE Merchant ADD COLUMN tanggal_daftar DATE DEFAULT CURRENT_DATE;
