<?php

require_once __DIR__ . '/../models/InfrastrukturObservasi.php';
require_once __DIR__ . '/../core/csrf.php';

class InfrastrukturObservasiController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new InfrastrukturObservasi($pdo);
    }

    /*
    =========================
    LIST INFRASTRUKTUR OBSERVASI
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET INFRASTRUKTUR OBSERVASI BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE INFRASTRUKTUR OBSERVASI
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_tanah = trim($data['id_tanah']);
        $periode_pengecekan = trim($data['periode_pengecekan']);
        $id_akses_perjalanan = trim($data['id_akses_perjalanan']);
        $id_kondisi_jalan = trim($data['id_kondisi_jalan']);
        $jarak_ke_jalan_km = trim($data['jarak_ke_jalan_km']);
        $ada_jembatan = trim($data['ada_jembatan']);
        $ada_listrik = trim($data['ada_listrik']);
        $ada_internet = trim($data['ada_internet']);
        $sinyal_seluler = trim($data['sinyal_seluler']);
        $catatan = trim($data['catatan']);

        return $this->model->create([
            'id_tanah' => $id_tanah,
            'periode_pengecekan' => $periode_pengecekan,
            'id_akses_perjalanan' => $id_akses_perjalanan,
            'id_kondisi_jalan' => $id_kondisi_jalan,
            'jarak_ke_jalan_km' => $jarak_ke_jalan_km,
            'ada_jembatan' => $ada_jembatan,
            'ada_listrik' => $ada_listrik,
            'ada_internet' => $ada_internet,
            'sinyal_seluler' => $sinyal_seluler,
            'catatan' => $catatan,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE INFRASTRUKTUR OBSERVASI
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_tanah = trim($data['id_tanah']);
        $periode_pengecekan = trim($data['periode_pengecekan']);
        $id_akses_perjalanan = trim($data['id_akses_perjalanan']);
        $id_kondisi_jalan = trim($data['id_kondisi_jalan']);
        $jarak_ke_jalan_km = trim($data['jarak_ke_jalan_km']);
        $ada_jembatan = trim($data['ada_jembatan']);
        $ada_listrik = trim($data['ada_listrik']);
        $ada_internet = trim($data['ada_internet']);
        $sinyal_seluler = trim($data['sinyal_seluler']);
        $catatan = trim($data['catatan']);

        return $this->model->update($id, [
            'id_tanah' => $id_tanah,
            'periode_pengecekan' => $periode_pengecekan,
            'id_akses_perjalanan' => $id_akses_perjalanan,
            'id_kondisi_jalan' => $id_kondisi_jalan,
            'jarak_ke_jalan_km' => $jarak_ke_jalan_km,
            'ada_jembatan' => $ada_jembatan,
            'ada_listrik' => $ada_listrik,
            'ada_internet' => $ada_internet,
            'sinyal_seluler' => $sinyal_seluler,
            'catatan' => $catatan,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE INFRASTRUKTUR OBSERVASI (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->softDelete($id, $user_id);
    }

    public function getTanah()
    {
        return $this->model->getTanah();
    }

    public function getAksesPerjalanan()
    {
        return $this->model->getAksesPerjalanan();
    }

    public function getKondisiJalan()
    {
        return $this->model->getKondisiJalan();
    }
}