<?php

require_once __DIR__ . '/../models/Glosarium.php';
require_once __DIR__ . '/../core/csrf.php';

class GlosariumController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Glosarium($pdo);
    }

    /*
    =========================
    LIST GLOSARIUM
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET GLOSARIUM BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE GLOSARIUM
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $istilah = trim($data['istilah']);
        $istilah_lain = trim($data['istilah_lain']);
        $kategori = trim($data['kategori']);
        $definisi_singkat = trim($data['definisi_singkat']);
        $penjelasan_lengkap = trim($data['penjelasan_lengkap']);
        $bahasa_asal = trim($data['bahasa_asal']);
        $pengucapan = trim($data['pengucapan']);
        $sumber = trim($data['sumber']);
        $gambar = trim($data['gambar']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'istilah' => $istilah,
            'istilah_lain' => $istilah_lain,
            'kategori' => $kategori,
            'definisi_singkat' => $definisi_singkat,
            'penjelasan_lengkap' => $penjelasan_lengkap,
            'bahasa_asal' => $bahasa_asal,
            'pengucapan' => $pengucapan,
            'sumber' => $sumber,
            'gambar' => $gambar,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE GLOSARIUM
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $istilah = trim($data['istilah']);
        $istilah_lain = trim($data['istilah_lain']);
        $kategori = trim($data['kategori']);
        $definisi_singkat = trim($data['definisi_singkat']);
        $penjelasan_lengkap = trim($data['penjelasan_lengkap']);
        $bahasa_asal = trim($data['bahasa_asal']);
        $pengucapan = trim($data['pengucapan']);
        $sumber = trim($data['sumber']);
        $gambar = trim($data['gambar']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'istilah' => $istilah,
            'istilah_lain' => $istilah_lain,
            'kategori' => $kategori,
            'definisi_singkat' => $definisi_singkat,
            'penjelasan_lengkap' => $penjelasan_lengkap,
            'bahasa_asal' => $bahasa_asal,
            'pengucapan' => $pengucapan,
            'sumber' => $sumber,
            'gambar' => $gambar,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE GLOSARIUM (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->softDelete($id, $user_id);
    }
}