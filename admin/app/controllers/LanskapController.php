<?php

require_once __DIR__ . '/../models/Lanskap.php';
require_once __DIR__ . '/../core/csrf.php';

class LanskapController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Lanskap($pdo);
    }

    /*
    =========================
    LIST LANSKAP
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET LANSKAP BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE LANSKAP
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode = trim($data['kode']);
        $nama = trim($data['nama']);
        $deskripsi = trim($data['deskripsi']);
        $urutan = (int) $data['urutan'];
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'kode' => $kode,
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'urutan' => $urutan,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE LANSKAP
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode = trim($data['kode']);
        $nama = trim($data['nama']);
        $deskripsi = trim($data['deskripsi']);
        $urutan = (int) $data['urutan'];
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'kode' => $kode,
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'urutan' => $urutan,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE LANSKAP (SOFT DELETE)
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