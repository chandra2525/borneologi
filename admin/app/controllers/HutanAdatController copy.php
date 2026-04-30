<?php

require_once __DIR__ . '/../models/HutanAdat.php';
require_once __DIR__ . '/../core/csrf.php';

class HutanAdatController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new HutanAdat($pdo);
    }

    /*
    =========================
    LIST HUTAN ADAT
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET HUTAN ADAT BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE HUTAN ADAT
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_hutan_adat = trim($data['kode_hutan_adat']);
        $nama_hutan_adat = trim($data['nama_hutan_adat']);
        $id_masyarakat_hukum_adat = trim($data['id_masyarakat_hukum_adat']);
        $id_desa = trim($data['id_desa']);
        $nomor_sk = trim($data['nomor_sk']);
        $tanggal_sk = trim($data['tanggal_sk']);
        $id_status_kawasan = trim($data['id_status_kawasan']);
        $luas_ha = trim($data['luas_ha']);
        $geom_area = trim($data['geom_area']);
        $keterangan = trim($data['keterangan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'kode_hutan_adat' => $kode_hutan_adat,
            'nama_hutan_adat' => $nama_hutan_adat,
            'id_masyarakat_hukum_adat' => $id_masyarakat_hukum_adat,
            'id_desa' => $id_desa,
            'nomor_sk' => $nomor_sk,
            'tanggal_sk' => $tanggal_sk,
            'id_status_kawasan' => $id_status_kawasan,
            'luas_ha' => $luas_ha,
            'geom_area' => $geom_area,
            'keterangan' => $keterangan,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE HUTAN ADAT
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_hutan_adat = trim($data['kode_hutan_adat']);
        $nama_hutan_adat = trim($data['nama_hutan_adat']);
        $id_masyarakat_hukum_adat = trim($data['id_masyarakat_hukum_adat']);
        $id_desa = trim($data['id_desa']);
        $nomor_sk = trim($data['nomor_sk']);
        $tanggal_sk = trim($data['tanggal_sk']);
        $id_status_kawasan = trim($data['id_status_kawasan']);
        $luas_ha = trim($data['luas_ha']);
        $geom_area = trim($data['geom_area']);
        $keterangan = trim($data['keterangan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'kode_hutan_adat' => $kode_hutan_adat,
            'nama_hutan_adat' => $nama_hutan_adat,
            'id_masyarakat_hukum_adat' => $id_masyarakat_hukum_adat,
            'id_desa' => $id_desa,
            'nomor_sk' => $nomor_sk,
            'tanggal_sk' => $tanggal_sk,
            'id_status_kawasan' => $id_status_kawasan,
            'luas_ha' => $luas_ha,
            'geom_area' => $geom_area,
            'keterangan' => $keterangan,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE HUTAN ADAT (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->softDelete($id, $user_id);
    }

    public function getKelompokTani()
    {
        return $this->model->getKelompokTani();
    }

    public function getDesa()
    {
        return $this->model->getDesa();
    }

    public function getStatusKawasan()
    {
        return $this->model->getStatusKawasan();
    }
}