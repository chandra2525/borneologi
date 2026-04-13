<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/csrf.php';

class UserController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new User($pdo);
    }

    /*
    =========================
    LIST USERS
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET USER BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    public function getRoles()
    {
        return $this->model->getRoles();
    }

    /*
    =========================
    CREATE USER
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_role = (int) $data['id_role'];
        $username = trim($data['username']);
        $email = trim($data['email']);
        $password_hash = password_hash($data['password'], PASSWORD_ARGON2ID);
        $nama_lengkap = trim($data['nama_lengkap']);
        $nomor_hp = trim($data['nomor_hp']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'id_role' => $id_role,
            'username' => $username,
            'email' => $email,
            'password_hash' => $password_hash,
            'nama_lengkap' => $nama_lengkap,
            'nomor_hp' => $nomor_hp,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE USER
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_role = trim($data['id_role']);
        $username = trim($data['username']);
        $email = trim($data['email']);
        $password_hash = password_hash($data['password'], PASSWORD_ARGON2ID);
        $nama_lengkap = trim($data['nama_lengkap']);
        $nomor_hp = trim($data['nomor_hp']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'id_role' => $id_role,
            'username' => $username,
            'email' => $email,
            'password_hash' => $password_hash,
            'nama_lengkap' => $nama_lengkap,
            'nomor_hp' => $nomor_hp,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE USER (SOFT DELETE)
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