<?php

require_once __DIR__ . '/../models/JenisPohon.php';
require_once __DIR__ . '/../core/csrf.php';

class JenisPohonController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new JenisPohon($pdo);
    }

    /*
    =========================
    LIST JENIS POHON
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET JENIS POHON BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE JENIS POHON
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode = trim($data['kode']);
        $nama = trim($data['nama']);
        $nama_latin = trim($data['nama_latin']);
        $deskripsi = trim($data['deskripsi']);
        $urutan = (int) $data['urutan'];
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'kode' => $kode,
            'nama' => $nama,
            'nama_latin' => $nama_latin,
            'deskripsi' => $deskripsi,
            'urutan' => $urutan,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE JENIS POHON
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode = trim($data['kode']);
        $nama = trim($data['nama']);
        $nama_latin = trim($data['nama_latin']);
        $deskripsi = trim($data['deskripsi']);
        $urutan = (int) $data['urutan'];
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'kode' => $kode,
            'nama' => $nama,
            'nama_latin' => $nama_latin,
            'deskripsi' => $deskripsi,
            'urutan' => $urutan,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE JENIS POHON (SOFT DELETE)
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