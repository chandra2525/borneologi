<?php

require_once __DIR__ . '/../models/Provinsi.php';
require_once __DIR__ . '/../core/csrf.php';

class ProvinsiController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Provinsi($pdo);
    }

    /*
    =========================
    LIST PROVINSI
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET PROVINSI BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE PROVINSI
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_provinsi = trim($data['kode_provinsi']);
        $nama_provinsi = trim($data['nama_provinsi']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'kode_provinsi' => $kode_provinsi,
            'nama_provinsi' => $nama_provinsi,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE PROVINSI
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_provinsi = trim($data['kode_provinsi']);
        $nama_provinsi = trim($data['nama_provinsi']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'kode_provinsi' => $kode_provinsi,
            'nama_provinsi' => $nama_provinsi,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE PROVINSI (SOFT DELETE)
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