<?php

require_once __DIR__ . '/../models/MonitoringPenanaman.php';
require_once __DIR__ . '/../core/csrf.php';

class MonitoringPenanamanController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model =
            new MonitoringPenanaman($pdo);
    }

    public function index($id_bank_benih = null)
    {
        return $this->model->getAll($id_bank_benih);
    }

    public function find($id)
    {
        return $this->model->findById($id);
    }

    /**
     * CREATE Monitoring Penanaman.
     *
     * Monitoring hanya sebagai HEADER.
     */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            throw new Exception(
                'Invalid CSRF Token'
            );
        }

        $id_tanah =
            (int) ($data['id_tanah'] ?? 0);

        $periode_pengecekan =
            trim(
                $data['periode_pengecekan'] ?? ''
            );

        $tanggal_tanam =
            trim(
                $data['tanggal_tanam'] ?? ''
            );

        $tanggal_monitoring =
            trim(
                $data['tanggal_monitoring'] ?? ''
            );

        $id_progress_status_monitoring =
            (int) (
                $data[
                    'id_progress_status_monitoring'
                ] ?? 0
            );

        $catatan =
            trim(
                $data['catatan'] ?? ''
            );

        if ($id_tanah <= 0) {
            throw new Exception(
                'Tanah wajib dipilih.'
            );
        }

        if ($periode_pengecekan === '') {
            throw new Exception(
                'Periode wajib diisi.'
            );
        }

        if ($tanggal_tanam === '') {
            throw new Exception(
                'Tanggal tanam wajib diisi.'
            );
        }

        if ($id_progress_status_monitoring <= 0) {
            throw new Exception(
                'Status monitoring wajib dipilih.'
            );
        }

        if (
            $tanggal_monitoring !== ''
            && $tanggal_monitoring < $tanggal_tanam
        ) {
            throw new Exception(
                'Tanggal monitoring tidak boleh sebelum tanggal tanam.'
            );
        }

        $kode_monitoring =
            $this->model
                ->generateKodeMonitoring();

        return $this->model->create([
            'kode_monitoring' =>
                $kode_monitoring,

            'id_tanah' =>
                $id_tanah,

            'id_progress_status_monitoring' =>
                $id_progress_status_monitoring,

            'periode_pengecekan' =>
                $periode_pengecekan,

            'tanggal_tanam' =>
                $tanggal_tanam,

            'tanggal_monitoring' =>
                $tanggal_monitoring !== ''
                ? $tanggal_monitoring
                : null,

            'catatan' =>
                $catatan !== ''
                ? $catatan
                : null,

            'created_by' =>
                $user_id
        ]);
    }

    /**
     * UPDATE Monitoring Penanaman.
     */
    public function update(
        $id,
        $data,
        $user_id
    ) {
        if (!verifyCsrfToken()) {
            throw new Exception(
                'Invalid CSRF Token'
            );
        }

        $id_tanah =
            (int) ($data['id_tanah'] ?? 0);

        $periode_pengecekan =
            trim(
                $data['periode_pengecekan'] ?? ''
            );

        $tanggal_tanam =
            trim(
                $data['tanggal_tanam'] ?? ''
            );

        $tanggal_monitoring =
            trim(
                $data['tanggal_monitoring'] ?? ''
            );

        $id_progress_status_monitoring =
            (int) (
                $data[
                    'id_progress_status_monitoring'
                ] ?? 0
            );

        $catatan =
            trim(
                $data['catatan'] ?? ''
            );

        if ($id <= 0) {
            throw new Exception(
                'ID monitoring tidak valid.'
            );
        }

        if ($id_tanah <= 0) {
            throw new Exception(
                'Tanah wajib dipilih.'
            );
        }

        if ($periode_pengecekan === '') {
            throw new Exception(
                'Periode wajib diisi.'
            );
        }

        if ($tanggal_tanam === '') {
            throw new Exception(
                'Tanggal tanam wajib diisi.'
            );
        }

        if ($id_progress_status_monitoring <= 0) {
            throw new Exception(
                'Status monitoring wajib dipilih.'
            );
        }

        if (
            $tanggal_monitoring !== ''
            && $tanggal_monitoring < $tanggal_tanam
        ) {
            throw new Exception(
                'Tanggal monitoring tidak boleh sebelum tanggal tanam.'
            );
        }

        return $this->model->update(
            $id,
            [
                'id_tanah' =>
                    $id_tanah,

                'periode_pengecekan' =>
                    $periode_pengecekan,

                'tanggal_tanam' =>
                    $tanggal_tanam,

                'tanggal_monitoring' =>
                    $tanggal_monitoring !== ''
                    ? $tanggal_monitoring
                    : null,

                'id_progress_status_monitoring' =>
                    $id_progress_status_monitoring,

                'catatan' =>
                    $catatan !== ''
                    ? $catatan
                    : null,

                'updated_by' =>
                    $user_id
            ]
        );
    }

    /**
     * DELETE Monitoring.
     */
    public function delete(
        $id,
        $user_id
    ) {
        if (!verifyCsrfToken()) {
            throw new Exception(
                'Invalid CSRF Token'
            );
        }

        if ($id <= 0) {
            throw new Exception(
                'ID monitoring tidak valid.'
            );
        }

        return $this->model->softDelete(
            $id,
            $user_id
        );
    }

    public function getTanah()
    {
        return $this->model->getTanah();
    }

    public function getStatusMonitoring()
    {
        return $this->model->getStatusMonitoring();
    }

    // public function getBankBenih($id)
    // {
    //     return $this->model->getBankBenih($id);
    // }
}
?>