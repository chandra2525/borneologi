<?php

require_once __DIR__ . '/../models/Kabupaten.php';
require_once __DIR__ . '/../core/csrf.php';

class KabupatenController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Kabupaten($pdo);
    }

    /*
    =========================
    LIST KABUPATEN
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET KABUPATEN BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE KABUPATEN
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_provinsi = trim($data['id_provinsi']);
        $kode_kabupaten = trim($data['kode_kabupaten']);
        $nama_kabupaten = trim($data['nama_kabupaten']);
        $tipe = trim($data['tipe']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'id_provinsi' => $id_provinsi,
            'kode_kabupaten' => $kode_kabupaten,
            'nama_kabupaten' => $nama_kabupaten,
            'tipe' => $tipe,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE KABUPATEN
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_provinsi = trim($data['id_provinsi']);
        $kode_kabupaten = trim($data['kode_kabupaten']);
        $nama_kabupaten = trim($data['nama_kabupaten']);
        $tipe = trim($data['tipe']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'id_provinsi' => $id_provinsi,
            'kode_kabupaten' => $kode_kabupaten,
            'nama_kabupaten' => $nama_kabupaten,
            'tipe' => $tipe,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE KABUPATEN (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }
        return $this->model->softDelete($id, $user_id);
    }

    public function getProvinsi()
    {
        return $this->model->getProvinsi();
    }
}