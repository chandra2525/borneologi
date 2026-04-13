<?php

require_once __DIR__ . '/../models/Menu.php';
require_once __DIR__ . '/../core/csrf.php';

class MenuController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Menu($pdo);
    }

    /*
    =========================
    LIST MENUS
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    public function getParents()
    {
        return $this->model->getParents();
    }

    /*
    =========================
    GET MENU BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE MENU
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode = trim($data['kode']);
        $nama = trim($data['nama']);
        $path  = trim($data['path ']);
        $icon  = trim($data['icon ']);
        $id_parent  = trim($data['id_parent ']);
        $urutan = (int) $data['urutan'];
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'kode' => $kode,
            'nama' => $nama,
            'path' => $path,
            'icon' => $icon,
            'id_parent ' => $id_parent ,
            'urutan' => $urutan,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE MENU
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode = trim($data['kode']);
        $nama = trim($data['nama']);
        $path  = trim($data['path ']);
        $icon  = trim($data['icon ']);
        $id_parent  = trim($data['id_parent ']);
        $urutan = (int) $data['urutan'];
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'kode' => $kode,
            'nama' => $nama,
            'path ' => $path,
            'icon ' => $icon,
            'id_parent ' => $id_parent ,
            'urutan' => $urutan,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE MENU (SOFT DELETE)
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