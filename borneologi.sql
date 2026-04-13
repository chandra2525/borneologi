-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 04, 2026 at 09:20 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `borneologi`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_akses_perjalanan`
--

CREATE TABLE `m_akses_perjalanan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_desa`
--

CREATE TABLE `m_desa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_kecamatan` bigint(20) UNSIGNED NOT NULL,
  `kode_desa` char(3) NOT NULL,
  `nama_desa` varchar(150) NOT NULL,
  `tipe_wilayah` enum('desa','kelurahan') NOT NULL DEFAULT 'desa',
  `kode_pos` varchar(10) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_fitur_tambahan`
--

CREATE TABLE `m_fitur_tambahan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_fungsi_pohon`
--

CREATE TABLE `m_fungsi_pohon` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_jabatan_kelompok`
--

CREATE TABLE `m_jabatan_kelompok` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_jenis_palung`
--

CREATE TABLE `m_jenis_palung` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_jenis_pohon`
--

CREATE TABLE `m_jenis_pohon` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `nama_latin` varchar(200) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_kabupaten`
--

CREATE TABLE `m_kabupaten` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_provinsi` bigint(20) UNSIGNED NOT NULL,
  `kode_kabupaten` char(2) NOT NULL,
  `nama_kabupaten` varchar(150) NOT NULL,
  `tipe` enum('kabupaten','kota') NOT NULL DEFAULT 'kabupaten',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_kategori_area`
--

CREATE TABLE `m_kategori_area` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_kategori_kelompok`
--

CREATE TABLE `m_kategori_kelompok` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `is_masyarakat_hukum_adat` tinyint(1) NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_kecamatan`
--

CREATE TABLE `m_kecamatan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_kabupaten` bigint(20) UNSIGNED NOT NULL,
  `kode_kecamatan` char(3) NOT NULL,
  `nama_kecamatan` varchar(150) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_kecepatan_aliran`
--

CREATE TABLE `m_kecepatan_aliran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_kondisi_jalan`
--

CREATE TABLE `m_kondisi_jalan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_lanskap`
--

CREATE TABLE `m_lanskap` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_legalitas_lahan`
--

CREATE TABLE `m_legalitas_lahan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_menus`
--

CREATE TABLE `m_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `path` varchar(255) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `id_parent` bigint(20) UNSIGNED DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_negara`
--

CREATE TABLE `m_negara` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` char(3) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_penggunaan_lainnya`
--

CREATE TABLE `m_penggunaan_lainnya` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_penggunaan_pertanian`
--

CREATE TABLE `m_penggunaan_pertanian` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_progress_status_monitoring`
--

CREATE TABLE `m_progress_status_monitoring` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_provinsi`
--

CREATE TABLE `m_provinsi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_provinsi` char(2) NOT NULL,
  `nama_provinsi` varchar(150) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_roles`
--

CREATE TABLE `m_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_role_menu_access`
--

CREATE TABLE `m_role_menu_access` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_role` bigint(20) UNSIGNED NOT NULL,
  `id_menu` bigint(20) UNSIGNED NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT 1,
  `can_create` tinyint(1) NOT NULL DEFAULT 0,
  `can_update` tinyint(1) NOT NULL DEFAULT 0,
  `can_delete` tinyint(1) NOT NULL DEFAULT 0,
  `can_approve` tinyint(1) NOT NULL DEFAULT 0,
  `can_export` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_status_kawasan`
--

CREATE TABLE `m_status_kawasan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_tipe_penanaman`
--

CREATE TABLE `m_tipe_penanaman` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_tipe_penyimpanan_benih`
--

CREATE TABLE `m_tipe_penyimpanan_benih` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_warna_air`
--

CREATE TABLE `m_warna_air` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_bank_benih`
--

CREATE TABLE `t_bank_benih` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomor_aksesi` varchar(50) NOT NULL,
  `id_tanah` bigint(20) UNSIGNED NOT NULL,
  `id_negara` bigint(20) UNSIGNED NOT NULL,
  `nama_lokal` varchar(200) NOT NULL,
  `nama_ilmiah` varchar(200) DEFAULT NULL,
  `famili_tanaman` varchar(150) DEFAULT NULL,
  `provenance` varchar(200) DEFAULT NULL,
  `id_tipe_penyimpanan_benih` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_masuk` date NOT NULL,
  `jumlah_stok` decimal(14,2) NOT NULL DEFAULT 0.00,
  `satuan_stok` enum('butir','gram','kg','paket','bibit') NOT NULL DEFAULT 'butir',
  `kadar_air_persen` decimal(5,2) DEFAULT NULL,
  `viabilitas_persen` decimal(5,2) DEFAULT NULL,
  `ketinggian_mdpl` int(11) DEFAULT NULL,
  `masa_berlaku_sampai` date DEFAULT NULL,
  `lokasi_penyimpanan` varchar(200) DEFAULT NULL,
  `titik_koleksi_lat` decimal(10,7) DEFAULT NULL,
  `titik_koleksi_lng` decimal(10,7) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `t_detail_monitoring_penanaman`
--

CREATE TABLE `t_detail_monitoring_penanaman` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_monitoring` bigint(20) UNSIGNED NOT NULL,
  `id_bank_benih` bigint(20) UNSIGNED NOT NULL,
  `jumlah_ditanam` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `satuan` enum('butir','gram','kg','paket','bibit') NOT NULL DEFAULT 'bibit',
  `jumlah_hidup` int(10) UNSIGNED DEFAULT NULL,
  `jumlah_mati` int(10) UNSIGNED DEFAULT NULL,
  `tinggi_rata2_cm` decimal(8,2) DEFAULT NULL,
  `diameter_rata2_cm` decimal(8,2) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `t_hutan_adat`
--

CREATE TABLE `t_hutan_adat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_hutan_adat` varchar(60) NOT NULL,
  `nama_hutan_adat` varchar(200) NOT NULL,
  `id_masyarakat_hukum_adat` bigint(20) UNSIGNED NOT NULL,
  `id_desa` bigint(20) UNSIGNED DEFAULT NULL,
  `nomor_sk` varchar(120) DEFAULT NULL,
  `tanggal_sk` date DEFAULT NULL,
  `id_status_kawasan` bigint(20) UNSIGNED DEFAULT NULL,
  `luas_ha` decimal(12,2) NOT NULL DEFAULT 0.00,
  `geom_area` multipolygon DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `t_infrastruktur_observasi`
--

CREATE TABLE `t_infrastruktur_observasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_tanah` bigint(20) UNSIGNED NOT NULL,
  `periode_pengecekan` date NOT NULL,
  `id_akses_perjalanan` bigint(20) UNSIGNED DEFAULT NULL,
  `id_kondisi_jalan` bigint(20) UNSIGNED DEFAULT NULL,
  `jarak_ke_jalan_km` decimal(8,2) DEFAULT NULL,
  `ada_jembatan` tinyint(1) NOT NULL DEFAULT 0,
  `ada_listrik` tinyint(1) NOT NULL DEFAULT 0,
  `ada_internet` tinyint(1) NOT NULL DEFAULT 0,
  `sinyal_seluler` enum('tidak_ada','lemah','sedang','kuat') DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `t_kaleka`
--

CREATE TABLE `t_kaleka` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_kaleka` varchar(50) NOT NULL,
  `nama_kaleka` varchar(200) NOT NULL,
  `id_petani` bigint(20) UNSIGNED NOT NULL,
  `id_desa` bigint(20) UNSIGNED NOT NULL,
  `luas_ha` decimal(10,2) NOT NULL DEFAULT 0.00,
  `centroid_lat` decimal(10,7) DEFAULT NULL,
  `centroid_lng` decimal(10,7) DEFAULT NULL,
  `geom_area` multipolygon DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `t_kelompok_tani`
--

CREATE TABLE `t_kelompok_tani` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_kelompok` varchar(50) NOT NULL,
  `nama_kelompok` varchar(200) NOT NULL,
  `id_kategori_kelompok` bigint(20) UNSIGNED NOT NULL,
  `id_desa` bigint(20) UNSIGNED NOT NULL,
  `alamat` text DEFAULT NULL,
  `id_akses_perjalanan` bigint(20) UNSIGNED DEFAULT NULL,
  `id_kondisi_jalan` bigint(20) UNSIGNED DEFAULT NULL,
  `tahun_bentuk` smallint(5) UNSIGNED DEFAULT NULL,
  `nomor_sk` varchar(100) DEFAULT NULL,
  `tanggal_sk` date DEFAULT NULL,
  `status_kelompok` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_land_cover_observasi`
--

CREATE TABLE `t_land_cover_observasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_tanah` bigint(20) UNSIGNED NOT NULL,
  `periode_pengecekan` date NOT NULL,
  `id_kategori_area` bigint(20) UNSIGNED NOT NULL,
  `id_penggunaan_pertanian` bigint(20) UNSIGNED DEFAULT NULL,
  `id_penggunaan_lainnya` bigint(20) UNSIGNED DEFAULT NULL,
  `persentase_tutupan` decimal(5,2) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `t_monitoring_penanaman`
--

CREATE TABLE `t_monitoring_penanaman` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_monitoring` varchar(60) NOT NULL,
  `id_tanah` bigint(20) UNSIGNED NOT NULL,
  `id_tipe_penanaman` bigint(20) UNSIGNED DEFAULT NULL,
  `id_progress_status_monitoring` bigint(20) UNSIGNED NOT NULL,
  `periode_pengecekan` date NOT NULL,
  `tanggal_tanam` date DEFAULT NULL,
  `tanggal_monitoring` date NOT NULL,
  `luas_tanam_ha` decimal(10,2) NOT NULL DEFAULT 0.00,
  `survival_rate_persen` decimal(5,2) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `t_perairan_observasi`
--

CREATE TABLE `t_perairan_observasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_tanah` bigint(20) UNSIGNED NOT NULL,
  `periode_pengecekan` date NOT NULL,
  `id_warna_air` bigint(20) UNSIGNED DEFAULT NULL,
  `id_jenis_palung` bigint(20) UNSIGNED DEFAULT NULL,
  `id_kecepatan_aliran` bigint(20) UNSIGNED DEFAULT NULL,
  `kedalaman_cm` decimal(8,2) DEFAULT NULL,
  `lebar_m` decimal(8,2) DEFAULT NULL,
  `debit_lps` decimal(10,2) DEFAULT NULL,
  `ph` decimal(4,2) DEFAULT NULL,
  `kekeruhan_ntu` decimal(8,2) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `t_petani`
--

CREATE TABLE `t_petani` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nik` char(16) DEFAULT NULL,
  `no_kk` char(16) DEFAULT NULL,
  `nama_lengkap` varchar(200) NOT NULL,
  `nama_panggilan` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `nomor_hp` varchar(20) DEFAULT NULL,
  `id_desa` bigint(20) UNSIGNED NOT NULL,
  `alamat` text DEFAULT NULL,
  `status_petani` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_petani_kelompok`
--

CREATE TABLE `t_petani_kelompok` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_petani` bigint(20) UNSIGNED NOT NULL,
  `id_kelompok_tani` bigint(20) UNSIGNED NOT NULL,
  `id_jabatan_kelompok` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_gabung` date NOT NULL,
  `tanggal_keluar` date DEFAULT NULL,
  `is_pengurus` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `t_pohon_observasi`
--

CREATE TABLE `t_pohon_observasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_tanah` bigint(20) UNSIGNED NOT NULL,
  `periode_pengecekan` date NOT NULL,
  `id_jenis_pohon` bigint(20) UNSIGNED NOT NULL,
  `id_fungsi_pohon` bigint(20) UNSIGNED DEFAULT NULL,
  `jumlah_pohon` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `diameter_rata2_cm` decimal(8,2) DEFAULT NULL,
  `tinggi_rata2_m` decimal(8,2) DEFAULT NULL,
  `kondisi` enum('baik','sedang','buruk') DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `t_tanah`
--

CREATE TABLE `t_tanah` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_tanah` varchar(60) NOT NULL,
  `id_petani` bigint(20) UNSIGNED NOT NULL,
  `id_kaleka` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_lahan` varchar(200) DEFAULT NULL,
  `id_legalitas_lahan` bigint(20) UNSIGNED DEFAULT NULL,
  `id_status_kawasan` bigint(20) UNSIGNED DEFAULT NULL,
  `luas_ha` decimal(10,2) NOT NULL DEFAULT 0.00,
  `centroid_lat` decimal(10,7) DEFAULT NULL,
  `centroid_lng` decimal(10,7) DEFAULT NULL,
  `geom_area` multipolygon DEFAULT NULL,
  `alamat_lokasi` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `sudah_validasi` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal_validasi` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `t_topografi_observasi`
--

CREATE TABLE `t_topografi_observasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_tanah` bigint(20) UNSIGNED NOT NULL,
  `periode_pengecekan` date NOT NULL,
  `id_lanskap` bigint(20) UNSIGNED DEFAULT NULL,
  `id_fitur_tambahan` bigint(20) UNSIGNED DEFAULT NULL,
  `elevasi_mdpl` int(11) DEFAULT NULL,
  `kemiringan_derajat` decimal(5,2) DEFAULT NULL,
  `rawan_erosi` tinyint(1) NOT NULL DEFAULT 0,
  `arah_lereng` varchar(50) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `t_users`
--

CREATE TABLE `t_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_role` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nama_lengkap` varchar(200) NOT NULL,
  `nomor_hp` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `failed_login_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_akses_perjalanan`
--
ALTER TABLE `m_akses_perjalanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_akses_perjalanan_kode` (`kode`),
  ADD KEY `idx_m_akses_perjalanan_nama` (`nama`),
  ADD KEY `idx_m_akses_perjalanan_active` (`is_active`),
  ADD KEY `idx_m_akses_perjalanan_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_desa`
--
ALTER TABLE `m_desa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_desa_kode_per_kec` (`id_kecamatan`,`kode_desa`),
  ADD KEY `idx_m_desa_nama` (`nama_desa`),
  ADD KEY `idx_m_desa_active` (`is_active`),
  ADD KEY `idx_m_desa_deleted_at` (`deleted_at`),
  ADD KEY `idx_m_desa_kecamatan` (`id_kecamatan`);

--
-- Indexes for table `m_fitur_tambahan`
--
ALTER TABLE `m_fitur_tambahan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_fitur_tambahan_kode` (`kode`),
  ADD KEY `idx_m_fitur_tambahan_nama` (`nama`),
  ADD KEY `idx_m_fitur_tambahan_active` (`is_active`),
  ADD KEY `idx_m_fitur_tambahan_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_fungsi_pohon`
--
ALTER TABLE `m_fungsi_pohon`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_fungsi_pohon_kode` (`kode`),
  ADD KEY `idx_m_fungsi_pohon_nama` (`nama`),
  ADD KEY `idx_m_fungsi_pohon_active` (`is_active`),
  ADD KEY `idx_m_fungsi_pohon_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_jabatan_kelompok`
--
ALTER TABLE `m_jabatan_kelompok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_jabatan_kelompok_kode` (`kode`),
  ADD KEY `idx_m_jabatan_kelompok_nama` (`nama`),
  ADD KEY `idx_m_jabatan_kelompok_active` (`is_active`),
  ADD KEY `idx_m_jabatan_kelompok_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_jenis_palung`
--
ALTER TABLE `m_jenis_palung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_jenis_palung_kode` (`kode`),
  ADD KEY `idx_m_jenis_palung_nama` (`nama`),
  ADD KEY `idx_m_jenis_palung_active` (`is_active`),
  ADD KEY `idx_m_jenis_palung_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_jenis_pohon`
--
ALTER TABLE `m_jenis_pohon`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_jenis_pohon_kode` (`kode`),
  ADD KEY `idx_m_jenis_pohon_nama` (`nama`),
  ADD KEY `idx_m_jenis_pohon_active` (`is_active`),
  ADD KEY `idx_m_jenis_pohon_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_kabupaten`
--
ALTER TABLE `m_kabupaten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_kabupaten_kode_per_prov` (`id_provinsi`,`kode_kabupaten`),
  ADD KEY `idx_m_kabupaten_nama` (`nama_kabupaten`),
  ADD KEY `idx_m_kabupaten_active` (`is_active`),
  ADD KEY `idx_m_kabupaten_deleted_at` (`deleted_at`),
  ADD KEY `idx_m_kabupaten_provinsi` (`id_provinsi`);

--
-- Indexes for table `m_kategori_area`
--
ALTER TABLE `m_kategori_area`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_kategori_area_kode` (`kode`),
  ADD KEY `idx_m_kategori_area_nama` (`nama`),
  ADD KEY `idx_m_kategori_area_active` (`is_active`),
  ADD KEY `idx_m_kategori_area_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_kategori_kelompok`
--
ALTER TABLE `m_kategori_kelompok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_kategori_kelompok_kode` (`kode`),
  ADD KEY `idx_m_kategori_kelompok_nama` (`nama`),
  ADD KEY `idx_m_kategori_kelompok_mha` (`is_masyarakat_hukum_adat`),
  ADD KEY `idx_m_kategori_kelompok_active` (`is_active`),
  ADD KEY `idx_m_kategori_kelompok_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_kecamatan`
--
ALTER TABLE `m_kecamatan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_kecamatan_kode_per_kab` (`id_kabupaten`,`kode_kecamatan`),
  ADD KEY `idx_m_kecamatan_nama` (`nama_kecamatan`),
  ADD KEY `idx_m_kecamatan_active` (`is_active`),
  ADD KEY `idx_m_kecamatan_deleted_at` (`deleted_at`),
  ADD KEY `idx_m_kecamatan_kabupaten` (`id_kabupaten`);

--
-- Indexes for table `m_kecepatan_aliran`
--
ALTER TABLE `m_kecepatan_aliran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_kecepatan_aliran_kode` (`kode`),
  ADD KEY `idx_m_kecepatan_aliran_nama` (`nama`),
  ADD KEY `idx_m_kecepatan_aliran_active` (`is_active`),
  ADD KEY `idx_m_kecepatan_aliran_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_kondisi_jalan`
--
ALTER TABLE `m_kondisi_jalan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_kondisi_jalan_kode` (`kode`),
  ADD KEY `idx_m_kondisi_jalan_nama` (`nama`),
  ADD KEY `idx_m_kondisi_jalan_active` (`is_active`),
  ADD KEY `idx_m_kondisi_jalan_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_lanskap`
--
ALTER TABLE `m_lanskap`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_lanskap_kode` (`kode`),
  ADD KEY `idx_m_lanskap_nama` (`nama`),
  ADD KEY `idx_m_lanskap_active` (`is_active`),
  ADD KEY `idx_m_lanskap_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_legalitas_lahan`
--
ALTER TABLE `m_legalitas_lahan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_legalitas_lahan_kode` (`kode`),
  ADD KEY `idx_m_legalitas_lahan_nama` (`nama`),
  ADD KEY `idx_m_legalitas_lahan_active` (`is_active`),
  ADD KEY `idx_m_legalitas_lahan_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_menus`
--
ALTER TABLE `m_menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_menus_kode` (`kode`),
  ADD UNIQUE KEY `uk_m_menus_path` (`path`),
  ADD KEY `idx_m_menus_parent` (`id_parent`),
  ADD KEY `idx_m_menus_nama` (`nama`),
  ADD KEY `idx_m_menus_active` (`is_active`),
  ADD KEY `idx_m_menus_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_negara`
--
ALTER TABLE `m_negara`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_negara_kode` (`kode`),
  ADD KEY `idx_m_negara_nama` (`nama`),
  ADD KEY `idx_m_negara_active` (`is_active`),
  ADD KEY `idx_m_negara_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_penggunaan_lainnya`
--
ALTER TABLE `m_penggunaan_lainnya`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_penggunaan_lainnya_kode` (`kode`),
  ADD KEY `idx_m_penggunaan_lainnya_nama` (`nama`),
  ADD KEY `idx_m_penggunaan_lainnya_active` (`is_active`),
  ADD KEY `idx_m_penggunaan_lainnya_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_penggunaan_pertanian`
--
ALTER TABLE `m_penggunaan_pertanian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_penggunaan_pertanian_kode` (`kode`),
  ADD KEY `idx_m_penggunaan_pertanian_nama` (`nama`),
  ADD KEY `idx_m_penggunaan_pertanian_active` (`is_active`),
  ADD KEY `idx_m_penggunaan_pertanian_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_progress_status_monitoring`
--
ALTER TABLE `m_progress_status_monitoring`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_progress_status_monitoring_kode` (`kode`),
  ADD KEY `idx_m_progress_status_monitoring_nama` (`nama`),
  ADD KEY `idx_m_progress_status_monitoring_active` (`is_active`),
  ADD KEY `idx_m_progress_status_monitoring_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_provinsi`
--
ALTER TABLE `m_provinsi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_provinsi_kode` (`kode_provinsi`),
  ADD KEY `idx_m_provinsi_nama` (`nama_provinsi`),
  ADD KEY `idx_m_provinsi_active` (`is_active`),
  ADD KEY `idx_m_provinsi_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_roles`
--
ALTER TABLE `m_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_roles_kode` (`kode`),
  ADD KEY `idx_m_roles_nama` (`nama`),
  ADD KEY `idx_m_roles_active` (`is_active`),
  ADD KEY `idx_m_roles_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_role_menu_access`
--
ALTER TABLE `m_role_menu_access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_role_menu_access_role_menu` (`id_role`,`id_menu`),
  ADD KEY `idx_m_role_menu_access_role` (`id_role`),
  ADD KEY `idx_m_role_menu_access_menu` (`id_menu`),
  ADD KEY `idx_m_role_menu_access_active` (`is_active`),
  ADD KEY `idx_m_role_menu_access_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_status_kawasan`
--
ALTER TABLE `m_status_kawasan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_status_kawasan_kode` (`kode`),
  ADD KEY `idx_m_status_kawasan_nama` (`nama`),
  ADD KEY `idx_m_status_kawasan_active` (`is_active`),
  ADD KEY `idx_m_status_kawasan_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_tipe_penanaman`
--
ALTER TABLE `m_tipe_penanaman`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_tipe_penanaman_kode` (`kode`),
  ADD KEY `idx_m_tipe_penanaman_nama` (`nama`),
  ADD KEY `idx_m_tipe_penanaman_active` (`is_active`),
  ADD KEY `idx_m_tipe_penanaman_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_tipe_penyimpanan_benih`
--
ALTER TABLE `m_tipe_penyimpanan_benih`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_tipe_penyimpanan_benih_kode` (`kode`),
  ADD KEY `idx_m_tipe_penyimpanan_benih_nama` (`nama`),
  ADD KEY `idx_m_tipe_penyimpanan_benih_active` (`is_active`),
  ADD KEY `idx_m_tipe_penyimpanan_benih_deleted_at` (`deleted_at`);

--
-- Indexes for table `m_warna_air`
--
ALTER TABLE `m_warna_air`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_m_warna_air_kode` (`kode`),
  ADD KEY `idx_m_warna_air_nama` (`nama`),
  ADD KEY `idx_m_warna_air_active` (`is_active`),
  ADD KEY `idx_m_warna_air_deleted_at` (`deleted_at`);

--
-- Indexes for table `t_bank_benih`
--
ALTER TABLE `t_bank_benih`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_bank_benih_aksesi` (`nomor_aksesi`),
  ADD KEY `idx_t_bank_benih_tanah` (`id_tanah`),
  ADD KEY `idx_t_bank_benih_negara` (`id_negara`),
  ADD KEY `idx_t_bank_benih_tipe` (`id_tipe_penyimpanan_benih`),
  ADD KEY `idx_t_bank_benih_tgl_masuk` (`tanggal_masuk`),
  ADD KEY `idx_t_bank_benih_deleted_at` (`deleted_at`);

--
-- Indexes for table `t_detail_monitoring_penanaman`
--
ALTER TABLE `t_detail_monitoring_penanaman`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_detail_monitoring_pair` (`id_monitoring`,`id_bank_benih`),
  ADD KEY `idx_t_detail_monitoring_benih` (`id_bank_benih`),
  ADD KEY `idx_t_detail_monitoring_deleted_at` (`deleted_at`);

--
-- Indexes for table `t_hutan_adat`
--
ALTER TABLE `t_hutan_adat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_hutan_adat_kode` (`kode_hutan_adat`),
  ADD KEY `idx_t_hutan_adat_mha` (`id_masyarakat_hukum_adat`),
  ADD KEY `idx_t_hutan_adat_desa` (`id_desa`),
  ADD KEY `idx_t_hutan_adat_status` (`id_status_kawasan`),
  ADD KEY `idx_t_hutan_adat_deleted_at` (`deleted_at`);

--
-- Indexes for table `t_infrastruktur_observasi`
--
ALTER TABLE `t_infrastruktur_observasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_infra_tanah_periode` (`id_tanah`,`periode_pengecekan`),
  ADD KEY `idx_t_infra_tanah_periode` (`id_tanah`,`periode_pengecekan`),
  ADD KEY `idx_t_infra_deleted_at` (`deleted_at`),
  ADD KEY `fk_t_infra_akses` (`id_akses_perjalanan`),
  ADD KEY `fk_t_infra_kondisi_jalan` (`id_kondisi_jalan`);

--
-- Indexes for table `t_kaleka`
--
ALTER TABLE `t_kaleka`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_kaleka_kode` (`kode_kaleka`),
  ADD KEY `idx_t_kaleka_nama` (`nama_kaleka`),
  ADD KEY `idx_t_kaleka_petani` (`id_petani`),
  ADD KEY `idx_t_kaleka_desa` (`id_desa`),
  ADD KEY `idx_t_kaleka_deleted_at` (`deleted_at`);

--
-- Indexes for table `t_kelompok_tani`
--
ALTER TABLE `t_kelompok_tani`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_kelompok_kode` (`kode_kelompok`),
  ADD KEY `idx_t_kelompok_nama` (`nama_kelompok`),
  ADD KEY `idx_t_kelompok_desa` (`id_desa`),
  ADD KEY `idx_t_kelompok_kategori` (`id_kategori_kelompok`),
  ADD KEY `idx_t_kelompok_deleted_at` (`deleted_at`),
  ADD KEY `fk_t_kelompok_akses` (`id_akses_perjalanan`),
  ADD KEY `fk_t_kelompok_kondisi_jalan` (`id_kondisi_jalan`);

--
-- Indexes for table `t_land_cover_observasi`
--
ALTER TABLE `t_land_cover_observasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_land_cover_unique` (`id_tanah`,`periode_pengecekan`,`id_kategori_area`),
  ADD KEY `idx_t_land_cover_tanah_periode` (`id_tanah`,`periode_pengecekan`),
  ADD KEY `idx_t_land_cover_deleted_at` (`deleted_at`),
  ADD KEY `fk_t_land_cover_kategori` (`id_kategori_area`),
  ADD KEY `fk_t_land_cover_penggunaan_pertanian` (`id_penggunaan_pertanian`),
  ADD KEY `fk_t_land_cover_penggunaan_lainnya` (`id_penggunaan_lainnya`);

--
-- Indexes for table `t_monitoring_penanaman`
--
ALTER TABLE `t_monitoring_penanaman`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_monitoring_kode` (`kode_monitoring`),
  ADD KEY `idx_t_monitoring_tanah` (`id_tanah`),
  ADD KEY `idx_t_monitoring_tipe` (`id_tipe_penanaman`),
  ADD KEY `idx_t_monitoring_status` (`id_progress_status_monitoring`),
  ADD KEY `idx_t_monitoring_tanah_periode` (`id_tanah`,`periode_pengecekan`),
  ADD KEY `idx_t_monitoring_deleted_at` (`deleted_at`);

--
-- Indexes for table `t_perairan_observasi`
--
ALTER TABLE `t_perairan_observasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_perairan_tanah_periode` (`id_tanah`,`periode_pengecekan`),
  ADD KEY `idx_t_perairan_tanah_periode` (`id_tanah`,`periode_pengecekan`),
  ADD KEY `idx_t_perairan_deleted_at` (`deleted_at`),
  ADD KEY `fk_t_perairan_warna` (`id_warna_air`),
  ADD KEY `fk_t_perairan_palung` (`id_jenis_palung`),
  ADD KEY `fk_t_perairan_kecepatan` (`id_kecepatan_aliran`);

--
-- Indexes for table `t_petani`
--
ALTER TABLE `t_petani`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_petani_nik` (`nik`),
  ADD KEY `idx_t_petani_nama` (`nama_lengkap`),
  ADD KEY `idx_t_petani_desa` (`id_desa`),
  ADD KEY `idx_t_petani_deleted_at` (`deleted_at`);

--
-- Indexes for table `t_petani_kelompok`
--
ALTER TABLE `t_petani_kelompok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_petani_kelompok_hist` (`id_petani`,`id_kelompok_tani`,`tanggal_gabung`),
  ADD KEY `idx_t_petani_kelompok_petani` (`id_petani`),
  ADD KEY `idx_t_petani_kelompok_kelompok` (`id_kelompok_tani`),
  ADD KEY `idx_t_petani_kelompok_active` (`is_active`),
  ADD KEY `idx_t_petani_kelompok_deleted_at` (`deleted_at`),
  ADD KEY `fk_t_petani_kelompok_jabatan` (`id_jabatan_kelompok`);

--
-- Indexes for table `t_pohon_observasi`
--
ALTER TABLE `t_pohon_observasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_pohon_unique` (`id_tanah`,`periode_pengecekan`,`id_jenis_pohon`),
  ADD KEY `idx_t_pohon_tanah_periode` (`id_tanah`,`periode_pengecekan`),
  ADD KEY `idx_t_pohon_deleted_at` (`deleted_at`),
  ADD KEY `fk_t_pohon_jenis` (`id_jenis_pohon`),
  ADD KEY `fk_t_pohon_fungsi` (`id_fungsi_pohon`);

--
-- Indexes for table `t_tanah`
--
ALTER TABLE `t_tanah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_tanah_kode` (`kode_tanah`),
  ADD KEY `idx_t_tanah_petani` (`id_petani`),
  ADD KEY `idx_t_tanah_kaleka` (`id_kaleka`),
  ADD KEY `idx_t_tanah_legalitas` (`id_legalitas_lahan`),
  ADD KEY `idx_t_tanah_status` (`id_status_kawasan`),
  ADD KEY `idx_t_tanah_deleted_at` (`deleted_at`);

--
-- Indexes for table `t_topografi_observasi`
--
ALTER TABLE `t_topografi_observasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_topografi_tanah_periode` (`id_tanah`,`periode_pengecekan`),
  ADD KEY `idx_t_topografi_tanah_periode` (`id_tanah`,`periode_pengecekan`),
  ADD KEY `idx_t_topografi_deleted_at` (`deleted_at`),
  ADD KEY `fk_t_topografi_lanskap` (`id_lanskap`),
  ADD KEY `fk_t_topografi_fitur` (`id_fitur_tambahan`);

--
-- Indexes for table `t_users`
--
ALTER TABLE `t_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_t_users_username` (`username`),
  ADD UNIQUE KEY `uk_t_users_email` (`email`),
  ADD KEY `idx_t_users_role` (`id_role`),
  ADD KEY `idx_t_users_active` (`is_active`),
  ADD KEY `idx_t_users_deleted_at` (`deleted_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_akses_perjalanan`
--
ALTER TABLE `m_akses_perjalanan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_desa`
--
ALTER TABLE `m_desa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_fitur_tambahan`
--
ALTER TABLE `m_fitur_tambahan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_fungsi_pohon`
--
ALTER TABLE `m_fungsi_pohon`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_jabatan_kelompok`
--
ALTER TABLE `m_jabatan_kelompok`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_jenis_palung`
--
ALTER TABLE `m_jenis_palung`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_jenis_pohon`
--
ALTER TABLE `m_jenis_pohon`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_kabupaten`
--
ALTER TABLE `m_kabupaten`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_kategori_area`
--
ALTER TABLE `m_kategori_area`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_kategori_kelompok`
--
ALTER TABLE `m_kategori_kelompok`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_kecamatan`
--
ALTER TABLE `m_kecamatan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_kecepatan_aliran`
--
ALTER TABLE `m_kecepatan_aliran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_kondisi_jalan`
--
ALTER TABLE `m_kondisi_jalan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_lanskap`
--
ALTER TABLE `m_lanskap`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_legalitas_lahan`
--
ALTER TABLE `m_legalitas_lahan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_menus`
--
ALTER TABLE `m_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_negara`
--
ALTER TABLE `m_negara`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_penggunaan_lainnya`
--
ALTER TABLE `m_penggunaan_lainnya`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_penggunaan_pertanian`
--
ALTER TABLE `m_penggunaan_pertanian`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_progress_status_monitoring`
--
ALTER TABLE `m_progress_status_monitoring`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_provinsi`
--
ALTER TABLE `m_provinsi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_roles`
--
ALTER TABLE `m_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_role_menu_access`
--
ALTER TABLE `m_role_menu_access`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_status_kawasan`
--
ALTER TABLE `m_status_kawasan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_tipe_penanaman`
--
ALTER TABLE `m_tipe_penanaman`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_tipe_penyimpanan_benih`
--
ALTER TABLE `m_tipe_penyimpanan_benih`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_warna_air`
--
ALTER TABLE `m_warna_air`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_bank_benih`
--
ALTER TABLE `t_bank_benih`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_detail_monitoring_penanaman`
--
ALTER TABLE `t_detail_monitoring_penanaman`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_hutan_adat`
--
ALTER TABLE `t_hutan_adat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_infrastruktur_observasi`
--
ALTER TABLE `t_infrastruktur_observasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_kaleka`
--
ALTER TABLE `t_kaleka`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_kelompok_tani`
--
ALTER TABLE `t_kelompok_tani`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_land_cover_observasi`
--
ALTER TABLE `t_land_cover_observasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_monitoring_penanaman`
--
ALTER TABLE `t_monitoring_penanaman`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_perairan_observasi`
--
ALTER TABLE `t_perairan_observasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_petani`
--
ALTER TABLE `t_petani`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_petani_kelompok`
--
ALTER TABLE `t_petani_kelompok`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_pohon_observasi`
--
ALTER TABLE `t_pohon_observasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_tanah`
--
ALTER TABLE `t_tanah`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_topografi_observasi`
--
ALTER TABLE `t_topografi_observasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `m_desa`
--
ALTER TABLE `m_desa`
  ADD CONSTRAINT `fk_m_desa_kecamatan` FOREIGN KEY (`id_kecamatan`) REFERENCES `m_kecamatan` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `m_kabupaten`
--
ALTER TABLE `m_kabupaten`
  ADD CONSTRAINT `fk_m_kabupaten_provinsi` FOREIGN KEY (`id_provinsi`) REFERENCES `m_provinsi` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `m_kecamatan`
--
ALTER TABLE `m_kecamatan`
  ADD CONSTRAINT `fk_m_kecamatan_kabupaten` FOREIGN KEY (`id_kabupaten`) REFERENCES `m_kabupaten` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `m_menus`
--
ALTER TABLE `m_menus`
  ADD CONSTRAINT `fk_m_menus_parent` FOREIGN KEY (`id_parent`) REFERENCES `m_menus` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `m_role_menu_access`
--
ALTER TABLE `m_role_menu_access`
  ADD CONSTRAINT `fk_m_role_menu_access_menu` FOREIGN KEY (`id_menu`) REFERENCES `m_menus` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_m_role_menu_access_role` FOREIGN KEY (`id_role`) REFERENCES `m_roles` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `t_bank_benih`
--
ALTER TABLE `t_bank_benih`
  ADD CONSTRAINT `fk_t_bank_benih_negara` FOREIGN KEY (`id_negara`) REFERENCES `m_negara` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_bank_benih_tanah` FOREIGN KEY (`id_tanah`) REFERENCES `t_tanah` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_bank_benih_tipe` FOREIGN KEY (`id_tipe_penyimpanan_benih`) REFERENCES `m_tipe_penyimpanan_benih` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `t_detail_monitoring_penanaman`
--
ALTER TABLE `t_detail_monitoring_penanaman`
  ADD CONSTRAINT `fk_t_detail_benih` FOREIGN KEY (`id_bank_benih`) REFERENCES `t_bank_benih` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_detail_monitoring` FOREIGN KEY (`id_monitoring`) REFERENCES `t_monitoring_penanaman` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_hutan_adat`
--
ALTER TABLE `t_hutan_adat`
  ADD CONSTRAINT `fk_t_hutan_adat_desa` FOREIGN KEY (`id_desa`) REFERENCES `m_desa` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_hutan_adat_mha` FOREIGN KEY (`id_masyarakat_hukum_adat`) REFERENCES `t_kelompok_tani` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_hutan_adat_status` FOREIGN KEY (`id_status_kawasan`) REFERENCES `m_status_kawasan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `t_infrastruktur_observasi`
--
ALTER TABLE `t_infrastruktur_observasi`
  ADD CONSTRAINT `fk_t_infra_akses` FOREIGN KEY (`id_akses_perjalanan`) REFERENCES `m_akses_perjalanan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_infra_kondisi_jalan` FOREIGN KEY (`id_kondisi_jalan`) REFERENCES `m_kondisi_jalan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_infra_tanah` FOREIGN KEY (`id_tanah`) REFERENCES `t_tanah` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `t_kaleka`
--
ALTER TABLE `t_kaleka`
  ADD CONSTRAINT `fk_t_kaleka_desa` FOREIGN KEY (`id_desa`) REFERENCES `m_desa` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_kaleka_petani` FOREIGN KEY (`id_petani`) REFERENCES `t_petani` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `t_kelompok_tani`
--
ALTER TABLE `t_kelompok_tani`
  ADD CONSTRAINT `fk_t_kelompok_akses` FOREIGN KEY (`id_akses_perjalanan`) REFERENCES `m_akses_perjalanan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_kelompok_desa` FOREIGN KEY (`id_desa`) REFERENCES `m_desa` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_kelompok_kategori` FOREIGN KEY (`id_kategori_kelompok`) REFERENCES `m_kategori_kelompok` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_kelompok_kondisi_jalan` FOREIGN KEY (`id_kondisi_jalan`) REFERENCES `m_kondisi_jalan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `t_land_cover_observasi`
--
ALTER TABLE `t_land_cover_observasi`
  ADD CONSTRAINT `fk_t_land_cover_kategori` FOREIGN KEY (`id_kategori_area`) REFERENCES `m_kategori_area` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_land_cover_penggunaan_lainnya` FOREIGN KEY (`id_penggunaan_lainnya`) REFERENCES `m_penggunaan_lainnya` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_land_cover_penggunaan_pertanian` FOREIGN KEY (`id_penggunaan_pertanian`) REFERENCES `m_penggunaan_pertanian` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_land_cover_tanah` FOREIGN KEY (`id_tanah`) REFERENCES `t_tanah` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `t_monitoring_penanaman`
--
ALTER TABLE `t_monitoring_penanaman`
  ADD CONSTRAINT `fk_t_monitoring_progress` FOREIGN KEY (`id_progress_status_monitoring`) REFERENCES `m_progress_status_monitoring` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_monitoring_tanah` FOREIGN KEY (`id_tanah`) REFERENCES `t_tanah` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_monitoring_tipe` FOREIGN KEY (`id_tipe_penanaman`) REFERENCES `m_tipe_penanaman` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `t_perairan_observasi`
--
ALTER TABLE `t_perairan_observasi`
  ADD CONSTRAINT `fk_t_perairan_kecepatan` FOREIGN KEY (`id_kecepatan_aliran`) REFERENCES `m_kecepatan_aliran` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_perairan_palung` FOREIGN KEY (`id_jenis_palung`) REFERENCES `m_jenis_palung` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_perairan_tanah` FOREIGN KEY (`id_tanah`) REFERENCES `t_tanah` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_perairan_warna` FOREIGN KEY (`id_warna_air`) REFERENCES `m_warna_air` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `t_petani`
--
ALTER TABLE `t_petani`
  ADD CONSTRAINT `fk_t_petani_desa` FOREIGN KEY (`id_desa`) REFERENCES `m_desa` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `t_petani_kelompok`
--
ALTER TABLE `t_petani_kelompok`
  ADD CONSTRAINT `fk_t_petani_kelompok_jabatan` FOREIGN KEY (`id_jabatan_kelompok`) REFERENCES `m_jabatan_kelompok` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_petani_kelompok_kelompok` FOREIGN KEY (`id_kelompok_tani`) REFERENCES `t_kelompok_tani` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_petani_kelompok_petani` FOREIGN KEY (`id_petani`) REFERENCES `t_petani` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `t_pohon_observasi`
--
ALTER TABLE `t_pohon_observasi`
  ADD CONSTRAINT `fk_t_pohon_fungsi` FOREIGN KEY (`id_fungsi_pohon`) REFERENCES `m_fungsi_pohon` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_pohon_jenis` FOREIGN KEY (`id_jenis_pohon`) REFERENCES `m_jenis_pohon` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_pohon_tanah` FOREIGN KEY (`id_tanah`) REFERENCES `t_tanah` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `t_tanah`
--
ALTER TABLE `t_tanah`
  ADD CONSTRAINT `fk_t_tanah_kaleka` FOREIGN KEY (`id_kaleka`) REFERENCES `t_kaleka` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_tanah_legalitas` FOREIGN KEY (`id_legalitas_lahan`) REFERENCES `m_legalitas_lahan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_tanah_petani` FOREIGN KEY (`id_petani`) REFERENCES `t_petani` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_tanah_status` FOREIGN KEY (`id_status_kawasan`) REFERENCES `m_status_kawasan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `t_topografi_observasi`
--
ALTER TABLE `t_topografi_observasi`
  ADD CONSTRAINT `fk_t_topografi_fitur` FOREIGN KEY (`id_fitur_tambahan`) REFERENCES `m_fitur_tambahan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_topografi_lanskap` FOREIGN KEY (`id_lanskap`) REFERENCES `m_lanskap` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_topografi_tanah` FOREIGN KEY (`id_tanah`) REFERENCES `t_tanah` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `t_users`
--
ALTER TABLE `t_users`
  ADD CONSTRAINT `fk_t_users_role` FOREIGN KEY (`id_role`) REFERENCES `m_roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
