<?php

require_once __DIR__ . '/../models/Petani.php';
require_once __DIR__ . '/../core/csrf.php';

class PetaniController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Petani($pdo);
    }

    /*
    =========================
    LIST PETANI
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET PETANI BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE PETANI
    =========================
    */
    public function store($data, $files, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $foto_profil_petani = null;

        // if (isset($files['foto_profil_petani']) && $files['foto_profil_petani']['error'] == 0) {

        //     $uploadDir = "../../uploads/petani/";

        //     if (!is_dir($uploadDir)) {
        //         mkdir($uploadDir, 0777, true);
        //     }

        //     $ext = pathinfo($files['foto_profil_petani']['name'], PATHINFO_EXTENSION);

        //     $fileName = time() . '_' . uniqid() . '.' . $ext;

        //     move_uploaded_file(
        //         $files['foto_profil_petani']['tmp_name'],
        //         $uploadDir . $fileName
        //     );

        //     $foto_profil_petani = $fileName;
        // }

        return $this->model->create([
            'nik' => trim($data['nik']),
            'no_kk' => trim($data['no_kk']),
            'nama_lengkap' => trim($data['nama_lengkap']),
            'nama_panggilan' => trim($data['nama_panggilan']),
            'jenis_kelamin' => trim($data['jenis_kelamin']),
            'tanggal_lahir' => trim($data['tanggal_lahir']),
            'nomor_hp' => trim($data['nomor_hp']),
            'id_desa' => trim($data['id_desa']),
            'alamat' => trim($data['alamat']),
            'status_petani' => trim($data['status_petani']),
            'foto_profil_petani' => $foto_profil_petani,
            'is_active' => $data['is_active'],
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE PETANI
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $nik = trim($data['nik']);
        $no_kk = trim($data['no_kk']);
        $nama_lengkap = trim($data['nama_lengkap']);
        $nama_panggilan = trim($data['nama_panggilan']);
        $jenis_kelamin = trim($data['jenis_kelamin']);
        $tanggal_lahir = trim($data['tanggal_lahir']);
        $nomor_hp = trim($data['nomor_hp']);
        $id_desa = trim($data['id_desa']);
        $alamat = trim($data['alamat']);
        $status_petani = trim($data['status_petani']);
        $foto_profil_petani = trim($data['foto_profil_petani']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'nik' => $nik,
            'no_kk' => $no_kk,
            'nama_lengkap' => $nama_lengkap,
            'nama_panggilan' => $nama_panggilan,
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'nomor_hp' => $nomor_hp,
            'id_desa' => $id_desa,
            'alamat' => $alamat,
            'status_petani' => $status_petani,
            'foto_profil_petani' => $foto_profil_petani,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE PETANI (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->softDelete($id, $user_id);
    }

    public function getDesa()
    {
        return $this->model->getDesa();
    }
}