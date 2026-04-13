-- =========================================================
-- FULL DDL DATABASE (MASTER + TRANSAKSI + AUTH)
-- MySQL 8.x
-- =========================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- =========================================================
-- A. MASTER WILAYAH
-- =========================================================

CREATE TABLE IF NOT EXISTS m_provinsi (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_provinsi CHAR(2) NOT NULL,
  nama_provinsi VARCHAR(150) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_provinsi_kode (kode_provinsi),
  KEY idx_m_provinsi_nama (nama_provinsi),
  KEY idx_m_provinsi_active (is_active),
  KEY idx_m_provinsi_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_kabupaten (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_provinsi BIGINT UNSIGNED NOT NULL,
  kode_kabupaten CHAR(2) NOT NULL,
  nama_kabupaten VARCHAR(150) NOT NULL,
  tipe ENUM('kabupaten','kota') NOT NULL DEFAULT 'kabupaten',
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_kabupaten_kode_per_prov (id_provinsi, kode_kabupaten),
  KEY idx_m_kabupaten_nama (nama_kabupaten),
  KEY idx_m_kabupaten_active (is_active),
  KEY idx_m_kabupaten_deleted_at (deleted_at),
  KEY idx_m_kabupaten_provinsi (id_provinsi),

  CONSTRAINT fk_m_kabupaten_provinsi
    FOREIGN KEY (id_provinsi) REFERENCES m_provinsi(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_kecamatan (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_kabupaten BIGINT UNSIGNED NOT NULL,
  kode_kecamatan CHAR(3) NOT NULL,
  nama_kecamatan VARCHAR(150) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_kecamatan_kode_per_kab (id_kabupaten, kode_kecamatan),
  KEY idx_m_kecamatan_nama (nama_kecamatan),
  KEY idx_m_kecamatan_active (is_active),
  KEY idx_m_kecamatan_deleted_at (deleted_at),
  KEY idx_m_kecamatan_kabupaten (id_kabupaten),

  CONSTRAINT fk_m_kecamatan_kabupaten
    FOREIGN KEY (id_kabupaten) REFERENCES m_kabupaten(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_desa (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_kecamatan BIGINT UNSIGNED NOT NULL,
  kode_desa CHAR(3) NOT NULL,
  nama_desa VARCHAR(150) NOT NULL,
  tipe_wilayah ENUM('desa','kelurahan') NOT NULL DEFAULT 'desa',
  kode_pos VARCHAR(10) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_desa_kode_per_kec (id_kecamatan, kode_desa),
  KEY idx_m_desa_nama (nama_desa),
  KEY idx_m_desa_active (is_active),
  KEY idx_m_desa_deleted_at (deleted_at),
  KEY idx_m_desa_kecamatan (id_kecamatan),

  CONSTRAINT fk_m_desa_kecamatan
    FOREIGN KEY (id_kecamatan) REFERENCES m_kecamatan(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- B. MASTER REFERENSI / LOOKUP
-- =========================================================

CREATE TABLE IF NOT EXISTS m_kategori_kelompok (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  is_masyarakat_hukum_adat TINYINT(1) NOT NULL DEFAULT 0,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_kategori_kelompok_kode (kode),
  KEY idx_m_kategori_kelompok_nama (nama),
  KEY idx_m_kategori_kelompok_mha (is_masyarakat_hukum_adat),
  KEY idx_m_kategori_kelompok_active (is_active),
  KEY idx_m_kategori_kelompok_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_jabatan_kelompok (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_jabatan_kelompok_kode (kode),
  KEY idx_m_jabatan_kelompok_nama (nama),
  KEY idx_m_jabatan_kelompok_active (is_active),
  KEY idx_m_jabatan_kelompok_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_akses_perjalanan (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_akses_perjalanan_kode (kode),
  KEY idx_m_akses_perjalanan_nama (nama),
  KEY idx_m_akses_perjalanan_active (is_active),
  KEY idx_m_akses_perjalanan_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_kondisi_jalan (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_kondisi_jalan_kode (kode),
  KEY idx_m_kondisi_jalan_nama (nama),
  KEY idx_m_kondisi_jalan_active (is_active),
  KEY idx_m_kondisi_jalan_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_legalitas_lahan (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_legalitas_lahan_kode (kode),
  KEY idx_m_legalitas_lahan_nama (nama),
  KEY idx_m_legalitas_lahan_active (is_active),
  KEY idx_m_legalitas_lahan_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_status_kawasan (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_status_kawasan_kode (kode),
  KEY idx_m_status_kawasan_nama (nama),
  KEY idx_m_status_kawasan_active (is_active),
  KEY idx_m_status_kawasan_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_tipe_penyimpanan_benih (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_tipe_penyimpanan_benih_kode (kode),
  KEY idx_m_tipe_penyimpanan_benih_nama (nama),
  KEY idx_m_tipe_penyimpanan_benih_active (is_active),
  KEY idx_m_tipe_penyimpanan_benih_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_tipe_penanaman (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_tipe_penanaman_kode (kode),
  KEY idx_m_tipe_penanaman_nama (nama),
  KEY idx_m_tipe_penanaman_active (is_active),
  KEY idx_m_tipe_penanaman_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_kategori_area (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_kategori_area_kode (kode),
  KEY idx_m_kategori_area_nama (nama),
  KEY idx_m_kategori_area_active (is_active),
  KEY idx_m_kategori_area_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_penggunaan_pertanian (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_penggunaan_pertanian_kode (kode),
  KEY idx_m_penggunaan_pertanian_nama (nama),
  KEY idx_m_penggunaan_pertanian_active (is_active),
  KEY idx_m_penggunaan_pertanian_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_penggunaan_lainnya (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_penggunaan_lainnya_kode (kode),
  KEY idx_m_penggunaan_lainnya_nama (nama),
  KEY idx_m_penggunaan_lainnya_active (is_active),
  KEY idx_m_penggunaan_lainnya_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_lanskap (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_lanskap_kode (kode),
  KEY idx_m_lanskap_nama (nama),
  KEY idx_m_lanskap_active (is_active),
  KEY idx_m_lanskap_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_fitur_tambahan (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_fitur_tambahan_kode (kode),
  KEY idx_m_fitur_tambahan_nama (nama),
  KEY idx_m_fitur_tambahan_active (is_active),
  KEY idx_m_fitur_tambahan_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_jenis_pohon (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  nama_latin VARCHAR(200) NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_jenis_pohon_kode (kode),
  KEY idx_m_jenis_pohon_nama (nama),
  KEY idx_m_jenis_pohon_active (is_active),
  KEY idx_m_jenis_pohon_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_fungsi_pohon (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_fungsi_pohon_kode (kode),
  KEY idx_m_fungsi_pohon_nama (nama),
  KEY idx_m_fungsi_pohon_active (is_active),
  KEY idx_m_fungsi_pohon_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_warna_air (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_warna_air_kode (kode),
  KEY idx_m_warna_air_nama (nama),
  KEY idx_m_warna_air_active (is_active),
  KEY idx_m_warna_air_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_jenis_palung (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_jenis_palung_kode (kode),
  KEY idx_m_jenis_palung_nama (nama),
  KEY idx_m_jenis_palung_active (is_active),
  KEY idx_m_jenis_palung_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_kecepatan_aliran (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_kecepatan_aliran_kode (kode),
  KEY idx_m_kecepatan_aliran_nama (nama),
  KEY idx_m_kecepatan_aliran_active (is_active),
  KEY idx_m_kecepatan_aliran_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_progress_status_monitoring (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_progress_status_monitoring_kode (kode),
  KEY idx_m_progress_status_monitoring_nama (nama),
  KEY idx_m_progress_status_monitoring_active (is_active),
  KEY idx_m_progress_status_monitoring_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_negara (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode CHAR(3) NOT NULL, -- ISO3, contoh IDN
  nama VARCHAR(150) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_negara_kode (kode),
  KEY idx_m_negara_nama (nama),
  KEY idx_m_negara_active (is_active),
  KEY idx_m_negara_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- C. MASTER AUTH / RBAC
-- =========================================================

CREATE TABLE IF NOT EXISTS m_roles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_roles_kode (kode),
  KEY idx_m_roles_nama (nama),
  KEY idx_m_roles_active (is_active),
  KEY idx_m_roles_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_menus (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL,
  nama VARCHAR(150) NOT NULL,
  path VARCHAR(255) NOT NULL,
  icon VARCHAR(100) NULL,
  id_parent BIGINT UNSIGNED NULL,
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_menus_kode (kode),
  UNIQUE KEY uk_m_menus_path (path),
  KEY idx_m_menus_parent (id_parent),
  KEY idx_m_menus_nama (nama),
  KEY idx_m_menus_active (is_active),
  KEY idx_m_menus_deleted_at (deleted_at),

  CONSTRAINT fk_m_menus_parent
    FOREIGN KEY (id_parent) REFERENCES m_menus(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS m_role_menu_access (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_role BIGINT UNSIGNED NOT NULL,
  id_menu BIGINT UNSIGNED NOT NULL,
  can_view TINYINT(1) NOT NULL DEFAULT 1,
  can_create TINYINT(1) NOT NULL DEFAULT 0,
  can_update TINYINT(1) NOT NULL DEFAULT 0,
  can_delete TINYINT(1) NOT NULL DEFAULT 0,
  can_approve TINYINT(1) NOT NULL DEFAULT 0,
  can_export TINYINT(1) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_m_role_menu_access_role_menu (id_role, id_menu),
  KEY idx_m_role_menu_access_role (id_role),
  KEY idx_m_role_menu_access_menu (id_menu),
  KEY idx_m_role_menu_access_active (is_active),
  KEY idx_m_role_menu_access_deleted_at (deleted_at),

  CONSTRAINT fk_m_role_menu_access_role
    FOREIGN KEY (id_role) REFERENCES m_roles(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_m_role_menu_access_menu
    FOREIGN KEY (id_menu) REFERENCES m_menus(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- D. TABEL TRANSAKSI
-- =========================================================

CREATE TABLE IF NOT EXISTS t_kelompok_tani (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_kelompok VARCHAR(50) NOT NULL,
  nama_kelompok VARCHAR(200) NOT NULL,
  id_kategori_kelompok BIGINT UNSIGNED NOT NULL,
  id_desa BIGINT UNSIGNED NOT NULL,
  alamat TEXT NULL,
  id_akses_perjalanan BIGINT UNSIGNED NULL,
  id_kondisi_jalan BIGINT UNSIGNED NULL,
  tahun_bentuk SMALLINT UNSIGNED NULL,
  nomor_sk VARCHAR(100) NULL,
  tanggal_sk DATE NULL,
  status_kelompok ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_kelompok_kode (kode_kelompok),
  KEY idx_t_kelompok_nama (nama_kelompok),
  KEY idx_t_kelompok_desa (id_desa),
  KEY idx_t_kelompok_kategori (id_kategori_kelompok),
  KEY idx_t_kelompok_deleted_at (deleted_at),

  CONSTRAINT fk_t_kelompok_kategori
    FOREIGN KEY (id_kategori_kelompok) REFERENCES m_kategori_kelompok(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_kelompok_desa
    FOREIGN KEY (id_desa) REFERENCES m_desa(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_kelompok_akses
    FOREIGN KEY (id_akses_perjalanan) REFERENCES m_akses_perjalanan(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_t_kelompok_kondisi_jalan
    FOREIGN KEY (id_kondisi_jalan) REFERENCES m_kondisi_jalan(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS t_petani (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nik CHAR(16) NULL,
  no_kk CHAR(16) NULL,
  nama_lengkap VARCHAR(200) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  tanggal_lahir DATE NULL,
  nomor_hp VARCHAR(20) NULL,
  id_desa BIGINT UNSIGNED NOT NULL,
  alamat TEXT NULL,
  status_petani ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_petani_nik (nik),
  KEY idx_t_petani_nama (nama_lengkap),
  KEY idx_t_petani_desa (id_desa),
  KEY idx_t_petani_deleted_at (deleted_at),

  CONSTRAINT fk_t_petani_desa
    FOREIGN KEY (id_desa) REFERENCES m_desa(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kaleka dimiliki Petani
CREATE TABLE IF NOT EXISTS t_kaleka (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_kaleka VARCHAR(50) NOT NULL,
  nama_kaleka VARCHAR(200) NOT NULL,
  id_petani BIGINT UNSIGNED NOT NULL,
  id_desa BIGINT UNSIGNED NOT NULL,
  luas_ha DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  centroid_lat DECIMAL(10,7) NULL,
  centroid_lng DECIMAL(10,7) NULL,
  geom_area MULTIPOLYGON NULL,
  keterangan TEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_kaleka_kode (kode_kaleka),
  KEY idx_t_kaleka_nama (nama_kaleka),
  KEY idx_t_kaleka_petani (id_petani),
  KEY idx_t_kaleka_desa (id_desa),
  KEY idx_t_kaleka_deleted_at (deleted_at),

  CONSTRAINT chk_t_kaleka_luas CHECK (luas_ha >= 0),

  CONSTRAINT fk_t_kaleka_petani
    FOREIGN KEY (id_petani) REFERENCES t_petani(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_kaleka_desa
    FOREIGN KEY (id_desa) REFERENCES m_desa(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS t_petani_kelompok (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_petani BIGINT UNSIGNED NOT NULL,
  id_kelompok_tani BIGINT UNSIGNED NOT NULL,
  id_jabatan_kelompok BIGINT UNSIGNED NULL,
  tanggal_gabung DATE NOT NULL,
  tanggal_keluar DATE NULL,
  is_pengurus TINYINT(1) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  keterangan TEXT NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_petani_kelompok_hist (id_petani, id_kelompok_tani, tanggal_gabung),
  KEY idx_t_petani_kelompok_petani (id_petani),
  KEY idx_t_petani_kelompok_kelompok (id_kelompok_tani),
  KEY idx_t_petani_kelompok_active (is_active),
  KEY idx_t_petani_kelompok_deleted_at (deleted_at),

  CONSTRAINT chk_t_petani_kelompok_tanggal
    CHECK (tanggal_keluar IS NULL OR tanggal_keluar >= tanggal_gabung),

  CONSTRAINT fk_t_petani_kelompok_petani
    FOREIGN KEY (id_petani) REFERENCES t_petani(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_petani_kelompok_kelompok
    FOREIGN KEY (id_kelompok_tani) REFERENCES t_kelompok_tani(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_petani_kelompok_jabatan
    FOREIGN KEY (id_jabatan_kelompok) REFERENCES m_jabatan_kelompok(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS t_tanah (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_tanah VARCHAR(60) NOT NULL,
  id_petani BIGINT UNSIGNED NOT NULL,
  id_kaleka BIGINT UNSIGNED NULL,
  nama_lahan VARCHAR(200) NULL,
  id_legalitas_lahan BIGINT UNSIGNED NULL,
  id_status_kawasan BIGINT UNSIGNED NULL,
  luas_ha DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  centroid_lat DECIMAL(10,7) NULL,
  centroid_lng DECIMAL(10,7) NULL,
  geom_area MULTIPOLYGON NULL,
  alamat_lokasi TEXT NULL,
  keterangan TEXT NULL,
  sudah_validasi TINYINT(1) NOT NULL DEFAULT 0,
  tanggal_validasi DATE NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_tanah_kode (kode_tanah),
  KEY idx_t_tanah_petani (id_petani),
  KEY idx_t_tanah_kaleka (id_kaleka),
  KEY idx_t_tanah_legalitas (id_legalitas_lahan),
  KEY idx_t_tanah_status (id_status_kawasan),
  KEY idx_t_tanah_deleted_at (deleted_at),

  CONSTRAINT chk_t_tanah_luas CHECK (luas_ha >= 0),

  CONSTRAINT fk_t_tanah_petani
    FOREIGN KEY (id_petani) REFERENCES t_petani(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_tanah_kaleka
    FOREIGN KEY (id_kaleka) REFERENCES t_kaleka(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_t_tanah_legalitas
    FOREIGN KEY (id_legalitas_lahan) REFERENCES m_legalitas_lahan(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_t_tanah_status
    FOREIGN KEY (id_status_kawasan) REFERENCES m_status_kawasan(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pengelola Hutan Adat = Masyarakat Hukum Adat (FK ke tabel kelompok)
CREATE TABLE IF NOT EXISTS t_hutan_adat (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_hutan_adat VARCHAR(60) NOT NULL,
  nama_hutan_adat VARCHAR(200) NOT NULL,
  id_masyarakat_hukum_adat BIGINT UNSIGNED NOT NULL, -- FK ke t_kelompok_tani
  id_desa BIGINT UNSIGNED NULL,
  nomor_sk VARCHAR(120) NULL,
  tanggal_sk DATE NULL,
  id_status_kawasan BIGINT UNSIGNED NULL,
  luas_ha DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  geom_area MULTIPOLYGON NULL,
  keterangan TEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_hutan_adat_kode (kode_hutan_adat),
  KEY idx_t_hutan_adat_mha (id_masyarakat_hukum_adat),
  KEY idx_t_hutan_adat_desa (id_desa),
  KEY idx_t_hutan_adat_status (id_status_kawasan),
  KEY idx_t_hutan_adat_deleted_at (deleted_at),

  CONSTRAINT chk_t_hutan_adat_luas CHECK (luas_ha >= 0),

  CONSTRAINT fk_t_hutan_adat_mha
    FOREIGN KEY (id_masyarakat_hukum_adat) REFERENCES t_kelompok_tani(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_hutan_adat_desa
    FOREIGN KEY (id_desa) REFERENCES m_desa(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_t_hutan_adat_status
    FOREIGN KEY (id_status_kawasan) REFERENCES m_status_kawasan(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS t_bank_benih (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nomor_aksesi VARCHAR(50) NOT NULL,
  id_tanah BIGINT UNSIGNED NOT NULL,
  id_negara BIGINT UNSIGNED NOT NULL,
  nama_lokal VARCHAR(200) NOT NULL,
  nama_ilmiah VARCHAR(200) NULL,
  famili_tanaman VARCHAR(150) NULL,
  provenance VARCHAR(200) NULL,
  id_tipe_penyimpanan_benih BIGINT UNSIGNED NULL,
  tanggal_masuk DATE NOT NULL,
  jumlah_stok DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  satuan_stok ENUM('butir','gram','kg','paket','bibit') NOT NULL DEFAULT 'butir',
  kadar_air_persen DECIMAL(5,2) NULL,
  viabilitas_persen DECIMAL(5,2) NULL,
  ketinggian_mdpl INT NULL,
  masa_berlaku_sampai DATE NULL,
  lokasi_penyimpanan VARCHAR(200) NULL,
  titik_koleksi_lat DECIMAL(10,7) NULL,
  titik_koleksi_lng DECIMAL(10,7) NULL,
  catatan TEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_bank_benih_aksesi (nomor_aksesi),
  KEY idx_t_bank_benih_tanah (id_tanah),
  KEY idx_t_bank_benih_negara (id_negara),
  KEY idx_t_bank_benih_tipe (id_tipe_penyimpanan_benih),
  KEY idx_t_bank_benih_tgl_masuk (tanggal_masuk),
  KEY idx_t_bank_benih_deleted_at (deleted_at),

  CONSTRAINT chk_t_bank_benih_stok CHECK (jumlah_stok >= 0),
  CONSTRAINT chk_t_bank_benih_kadar_air CHECK (kadar_air_persen IS NULL OR (kadar_air_persen >= 0 AND kadar_air_persen <= 100)),
  CONSTRAINT chk_t_bank_benih_viabilitas CHECK (viabilitas_persen IS NULL OR (viabilitas_persen >= 0 AND viabilitas_persen <= 100)),

  CONSTRAINT fk_t_bank_benih_tanah
    FOREIGN KEY (id_tanah) REFERENCES t_tanah(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_bank_benih_negara
    FOREIGN KEY (id_negara) REFERENCES m_negara(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_bank_benih_tipe
    FOREIGN KEY (id_tipe_penyimpanan_benih) REFERENCES m_tipe_penyimpanan_benih(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS t_monitoring_penanaman (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_monitoring VARCHAR(60) NOT NULL,
  id_tanah BIGINT UNSIGNED NOT NULL,
  id_tipe_penanaman BIGINT UNSIGNED NULL,
  id_progress_status_monitoring BIGINT UNSIGNED NOT NULL,
  periode_pengecekan DATE NOT NULL,
  tanggal_tanam DATE NULL,
  tanggal_monitoring DATE NOT NULL,
  luas_tanam_ha DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  survival_rate_persen DECIMAL(5,2) NULL,
  catatan TEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_monitoring_kode (kode_monitoring),
  KEY idx_t_monitoring_tanah (id_tanah),
  KEY idx_t_monitoring_tipe (id_tipe_penanaman),
  KEY idx_t_monitoring_status (id_progress_status_monitoring),
  KEY idx_t_monitoring_tanah_periode (id_tanah, periode_pengecekan DESC),
  KEY idx_t_monitoring_deleted_at (deleted_at),

  CONSTRAINT chk_t_monitoring_luas CHECK (luas_tanam_ha >= 0),
  CONSTRAINT chk_t_monitoring_survival CHECK (survival_rate_persen IS NULL OR (survival_rate_persen >= 0 AND survival_rate_persen <= 100)),

  CONSTRAINT fk_t_monitoring_tanah
    FOREIGN KEY (id_tanah) REFERENCES t_tanah(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_monitoring_tipe
    FOREIGN KEY (id_tipe_penanaman) REFERENCES m_tipe_penanaman(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_t_monitoring_progress
    FOREIGN KEY (id_progress_status_monitoring) REFERENCES m_progress_status_monitoring(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS t_detail_monitoring_penanaman (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_monitoring BIGINT UNSIGNED NOT NULL,
  id_bank_benih BIGINT UNSIGNED NOT NULL,
  jumlah_ditanam INT UNSIGNED NOT NULL DEFAULT 0,
  satuan ENUM('butir','gram','kg','paket','bibit') NOT NULL DEFAULT 'bibit',
  jumlah_hidup INT UNSIGNED NULL,
  jumlah_mati INT UNSIGNED NULL,
  tinggi_rata2_cm DECIMAL(8,2) NULL,
  diameter_rata2_cm DECIMAL(8,2) NULL,
  catatan TEXT NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_detail_monitoring_pair (id_monitoring, id_bank_benih),
  KEY idx_t_detail_monitoring_benih (id_bank_benih),
  KEY idx_t_detail_monitoring_deleted_at (deleted_at),

  CONSTRAINT chk_t_detail_jumlah_ditanam CHECK (jumlah_ditanam >= 0),
  CONSTRAINT chk_t_detail_jumlah_hidup CHECK (jumlah_hidup IS NULL OR jumlah_hidup >= 0),
  CONSTRAINT chk_t_detail_jumlah_mati CHECK (jumlah_mati IS NULL OR jumlah_mati >= 0),

  CONSTRAINT fk_t_detail_monitoring
    FOREIGN KEY (id_monitoring) REFERENCES t_monitoring_penanaman(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_t_detail_benih
    FOREIGN KEY (id_bank_benih) REFERENCES t_bank_benih(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS t_infrastruktur_observasi (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_tanah BIGINT UNSIGNED NOT NULL,
  periode_pengecekan DATE NOT NULL,
  id_akses_perjalanan BIGINT UNSIGNED NULL,
  id_kondisi_jalan BIGINT UNSIGNED NULL,
  jarak_ke_jalan_km DECIMAL(8,2) NULL,
  ada_jembatan TINYINT(1) NOT NULL DEFAULT 0,
  ada_listrik TINYINT(1) NOT NULL DEFAULT 0,
  ada_internet TINYINT(1) NOT NULL DEFAULT 0,
  sinyal_seluler ENUM('tidak_ada','lemah','sedang','kuat') NULL,
  catatan TEXT NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_infra_tanah_periode (id_tanah, periode_pengecekan),
  KEY idx_t_infra_tanah_periode (id_tanah, periode_pengecekan DESC),
  KEY idx_t_infra_deleted_at (deleted_at),

  CONSTRAINT chk_t_infra_jarak CHECK (jarak_ke_jalan_km IS NULL OR jarak_ke_jalan_km >= 0),

  CONSTRAINT fk_t_infra_tanah
    FOREIGN KEY (id_tanah) REFERENCES t_tanah(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_infra_akses
    FOREIGN KEY (id_akses_perjalanan) REFERENCES m_akses_perjalanan(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_t_infra_kondisi_jalan
    FOREIGN KEY (id_kondisi_jalan) REFERENCES m_kondisi_jalan(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS t_land_cover_observasi (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_tanah BIGINT UNSIGNED NOT NULL,
  periode_pengecekan DATE NOT NULL,
  id_kategori_area BIGINT UNSIGNED NOT NULL,
  id_penggunaan_pertanian BIGINT UNSIGNED NULL,
  id_penggunaan_lainnya BIGINT UNSIGNED NULL,
  persentase_tutupan DECIMAL(5,2) NULL,
  catatan TEXT NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_land_cover_unique (id_tanah, periode_pengecekan, id_kategori_area),
  KEY idx_t_land_cover_tanah_periode (id_tanah, periode_pengecekan DESC),
  KEY idx_t_land_cover_deleted_at (deleted_at),

  CONSTRAINT chk_t_land_cover_persen
    CHECK (persentase_tutupan IS NULL OR (persentase_tutupan >= 0 AND persentase_tutupan <= 100)),

  CONSTRAINT fk_t_land_cover_tanah
    FOREIGN KEY (id_tanah) REFERENCES t_tanah(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_land_cover_kategori
    FOREIGN KEY (id_kategori_area) REFERENCES m_kategori_area(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_land_cover_penggunaan_pertanian
    FOREIGN KEY (id_penggunaan_pertanian) REFERENCES m_penggunaan_pertanian(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_t_land_cover_penggunaan_lainnya
    FOREIGN KEY (id_penggunaan_lainnya) REFERENCES m_penggunaan_lainnya(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS t_topografi_observasi (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_tanah BIGINT UNSIGNED NOT NULL,
  periode_pengecekan DATE NOT NULL,
  id_lanskap BIGINT UNSIGNED NULL,
  id_fitur_tambahan BIGINT UNSIGNED NULL,
  elevasi_mdpl INT NULL,
  kemiringan_derajat DECIMAL(5,2) NULL,
  rawan_erosi TINYINT(1) NOT NULL DEFAULT 0,
  arah_lereng VARCHAR(50) NULL,
  catatan TEXT NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_topografi_tanah_periode (id_tanah, periode_pengecekan),
  KEY idx_t_topografi_tanah_periode (id_tanah, periode_pengecekan DESC),
  KEY idx_t_topografi_deleted_at (deleted_at),

  CONSTRAINT chk_t_topografi_kemiringan
    CHECK (kemiringan_derajat IS NULL OR (kemiringan_derajat >= 0 AND kemiringan_derajat <= 90)),

  CONSTRAINT fk_t_topografi_tanah
    FOREIGN KEY (id_tanah) REFERENCES t_tanah(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_topografi_lanskap
    FOREIGN KEY (id_lanskap) REFERENCES m_lanskap(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_t_topografi_fitur
    FOREIGN KEY (id_fitur_tambahan) REFERENCES m_fitur_tambahan(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS t_pohon_observasi (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_tanah BIGINT UNSIGNED NOT NULL,
  periode_pengecekan DATE NOT NULL,
  id_jenis_pohon BIGINT UNSIGNED NOT NULL,
  id_fungsi_pohon BIGINT UNSIGNED NULL,
  jumlah_pohon INT UNSIGNED NOT NULL DEFAULT 0,
  diameter_rata2_cm DECIMAL(8,2) NULL,
  tinggi_rata2_m DECIMAL(8,2) NULL,
  kondisi ENUM('baik','sedang','buruk') NULL,
  catatan TEXT NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_pohon_unique (id_tanah, periode_pengecekan, id_jenis_pohon),
  KEY idx_t_pohon_tanah_periode (id_tanah, periode_pengecekan DESC),
  KEY idx_t_pohon_deleted_at (deleted_at),

  CONSTRAINT chk_t_pohon_jumlah CHECK (jumlah_pohon >= 0),
  CONSTRAINT chk_t_pohon_diameter CHECK (diameter_rata2_cm IS NULL OR diameter_rata2_cm >= 0),
  CONSTRAINT chk_t_pohon_tinggi CHECK (tinggi_rata2_m IS NULL OR tinggi_rata2_m >= 0),

  CONSTRAINT fk_t_pohon_tanah
    FOREIGN KEY (id_tanah) REFERENCES t_tanah(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_pohon_jenis
    FOREIGN KEY (id_jenis_pohon) REFERENCES m_jenis_pohon(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_pohon_fungsi
    FOREIGN KEY (id_fungsi_pohon) REFERENCES m_fungsi_pohon(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS t_perairan_observasi (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_tanah BIGINT UNSIGNED NOT NULL,
  periode_pengecekan DATE NOT NULL,
  id_warna_air BIGINT UNSIGNED NULL,
  id_jenis_palung BIGINT UNSIGNED NULL,
  id_kecepatan_aliran BIGINT UNSIGNED NULL,
  kedalaman_cm DECIMAL(8,2) NULL,
  lebar_m DECIMAL(8,2) NULL,
  debit_lps DECIMAL(10,2) NULL,
  ph DECIMAL(4,2) NULL,
  kekeruhan_ntu DECIMAL(8,2) NULL,
  catatan TEXT NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_perairan_tanah_periode (id_tanah, periode_pengecekan),
  KEY idx_t_perairan_tanah_periode (id_tanah, periode_pengecekan DESC),
  KEY idx_t_perairan_deleted_at (deleted_at),

  CONSTRAINT chk_t_perairan_kedalaman CHECK (kedalaman_cm IS NULL OR kedalaman_cm >= 0),
  CONSTRAINT chk_t_perairan_lebar CHECK (lebar_m IS NULL OR lebar_m >= 0),
  CONSTRAINT chk_t_perairan_debit CHECK (debit_lps IS NULL OR debit_lps >= 0),
  CONSTRAINT chk_t_perairan_ph CHECK (ph IS NULL OR (ph >= 0 AND ph <= 14)),
  CONSTRAINT chk_t_perairan_kekeruhan CHECK (kekeruhan_ntu IS NULL OR kekeruhan_ntu >= 0),

  CONSTRAINT fk_t_perairan_tanah
    FOREIGN KEY (id_tanah) REFERENCES t_tanah(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_perairan_warna
    FOREIGN KEY (id_warna_air) REFERENCES m_warna_air(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_t_perairan_palung
    FOREIGN KEY (id_jenis_palung) REFERENCES m_jenis_palung(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_t_perairan_kecepatan
    FOREIGN KEY (id_kecepatan_aliran) REFERENCES m_kecepatan_aliran(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- E. TABEL USER OPERASIONAL
-- =========================================================

CREATE TABLE IF NOT EXISTS t_users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_role BIGINT UNSIGNED NOT NULL,
  id_desa BIGINT UNSIGNED NULL,
  id_kelompok_tani BIGINT UNSIGNED NULL,

  username VARCHAR(100) NOT NULL,
  email VARCHAR(191) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(200) NOT NULL,
  nomor_hp VARCHAR(20) NULL,

  is_active TINYINT(1) NOT NULL DEFAULT 1,
  must_change_password TINYINT(1) NOT NULL DEFAULT 0,
  failed_login_count INT UNSIGNED NOT NULL DEFAULT 0,
  locked_until DATETIME NULL,
  last_login_at DATETIME NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  created_by BIGINT UNSIGNED NULL,
  updated_by BIGINT UNSIGNED NULL,
  deleted_by BIGINT UNSIGNED NULL,

  UNIQUE KEY uk_t_users_username (username),
  UNIQUE KEY uk_t_users_email (email),
  KEY idx_t_users_role (id_role),
  KEY idx_t_users_desa (id_desa),
  KEY idx_t_users_kelompok (id_kelompok_tani),
  KEY idx_t_users_active (is_active),
  KEY idx_t_users_deleted_at (deleted_at),

  CONSTRAINT fk_t_users_role
    FOREIGN KEY (id_role) REFERENCES m_roles(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_t_users_desa
    FOREIGN KEY (id_desa) REFERENCES m_desa(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_t_users_kelompok
    FOREIGN KEY (id_kelompok_tani) REFERENCES t_kelompok_tani(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;