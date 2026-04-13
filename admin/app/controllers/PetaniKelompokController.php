<?php

require_once __DIR__ . '/../models/PetaniKelompok.php';
require_once __DIR__ . '/../core/csrf.php';

class PetaniKelompokController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new PetaniKelompok($pdo);
    }

    /*
    =========================
    LIST PETANI KELOMPOK
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET PETANI KELOMPOK BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE PETANI KELOMPOK
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_petani = trim($data['id_petani']);
        $id_kelompok_tani = trim($data['id_kelompok_tani']);
        $id_jabatan_kelompok = trim($data['id_jabatan_kelompok']);
        $tanggal_gabung = trim($data['tanggal_gabung']);
        $tanggal_keluar = trim($data['tanggal_keluar']);
        $is_pengurus = trim($data['is_pengurus']);
        $keterangan = trim($data['keterangan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'id_petani' => $id_petani,
            'id_kelompok_tani' => $id_kelompok_tani,
            'id_jabatan_kelompok' => $id_jabatan_kelompok,
            'tanggal_gabung' => $tanggal_gabung,
            'tanggal_keluar' => $tanggal_keluar,
            'is_pengurus' => $is_pengurus,
            'keterangan' => $keterangan,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE PETANI KELOMPOK
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_petani = trim($data['id_petani']);
        $id_kelompok_tani = trim($data['id_kelompok_tani']);
        $id_jabatan_kelompok = trim($data['id_jabatan_kelompok']);
        $tanggal_gabung = trim($data['tanggal_gabung']);
        $tanggal_keluar = trim($data['tanggal_keluar']);
        $is_pengurus = trim($data['is_pengurus']);
        $keterangan = trim($data['keterangan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'id_petani' => $id_petani,
            'id_kelompok_tani' => $id_kelompok_tani,
            'id_jabatan_kelompok' => $id_jabatan_kelompok,
            'tanggal_gabung' => $tanggal_gabung,
            'tanggal_keluar' => $tanggal_keluar,
            'is_pengurus' => $is_pengurus,
            'keterangan' => $keterangan,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE PETANI KELOMPOK (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->softDelete($id, $user_id);
    }

    public function getPetani()
    {
        return $this->model->getPetani();
    }

    public function getKelompokTani()
    {
        return $this->model->getKelompokTani();
    }

    public function getJabatanKelompok()
    {
        return $this->model->getJabatanKelompok();
    }
}