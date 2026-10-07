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

        $id_turunan = (int) ($data['id_turunan'] ?? 0);

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

        /*
         * Ambil nama status
         */
        $statusMonitoring = null;

        $statusList = $this->model->getStatusMonitoring();

        foreach ($statusList as $status) {
            if ((int) $status['id'] === $id_progress_status_monitoring) {
                $statusMonitoring = $status['nama'];
                break;
            }
        }

        if (!$statusMonitoring) {
            throw new Exception(
                'Progress Status Monitoring tidak valid.'
            );
        }

        /*
         * Jika status BUKAN Baru Ditanam,
         * maka wajib memilih monitoring awal.
         */
        if ($statusMonitoring !== 'Baru Ditanam') {

            if ($id_turunan <= 0) {
                throw new Exception(
                    'Monitoring awal wajib dipilih.'
                );
            }

            $monitoringAwal =
                $this->model->findMonitoringAwal($id_turunan);

            if (!$monitoringAwal) {
                throw new Exception(
                    'Monitoring awal tidak valid.'
                );
            }

            /*
             * Tanah dan tanggal tanam
             * selalu mengikuti monitoring awal.
             */
            $id_tanah =
                (int) $monitoringAwal['id_tanah'];

            $tanggal_tanam =
                $monitoringAwal['tanggal_tanam'];
        } else {

            /*
             * Status Baru Ditanam tidak mempunyai
             * monitoring awal.
             */
            $id_turunan = null;

            if ($id_tanah <= 0) {
                throw new Exception(
                    'Tanah wajib dipilih.'
                );
            }

            if ($tanggal_tanam === '') {
                throw new Exception(
                    'Tanggal tanam wajib diisi.'
                );
            }
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

        return $this->model->createWithInheritedDetails([
            'kode_monitoring' =>
                $kode_monitoring,

            'id_turunan' =>
                $id_turunan,

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

            'is_active' =>
                (int) ($data['is_active'] ?? 1)
        ], $user_id);
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

        if ($id <= 0) {
            throw new Exception(
                'ID monitoring tidak valid.'
            );
        }

        $id_turunan = (int) ($data['id_turunan'] ?? 0);

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

        /*
         * Ambil nama status
         */
        $statusMonitoring = null;

        $statusList = $this->model->getStatusMonitoring();

        foreach ($statusList as $status) {
            if ((int) $status['id'] === $id_progress_status_monitoring) {
                $statusMonitoring = $status['nama'];
                break;
            }
        }

        if (!$statusMonitoring) {
            throw new Exception(
                'Progress Status Monitoring tidak valid.'
            );
        }

        /*
         * Jika status bukan Baru Ditanam
         */
        if ($statusMonitoring !== 'Baru Ditanam') {

            if ($id_turunan <= 0) {
                throw new Exception(
                    'Monitoring awal wajib dipilih.'
                );
            }

            /*
             * Jangan boleh memilih dirinya sendiri
             */
            if ($id_turunan === (int) $id) {
                throw new Exception(
                    'Monitoring tidak boleh menjadi monitoring awal untuk dirinya sendiri.'
                );
            }

            $monitoringAwal =
                $this->model->findMonitoringAwal($id_turunan);

            if (!$monitoringAwal) {
                throw new Exception(
                    'Monitoring awal tidak valid.'
                );
            }

            /*
             * Tanah dan tanggal tanam
             * wajib mengikuti monitoring awal.
             */
            $id_tanah =
                (int) $monitoringAwal['id_tanah'];

            $tanggal_tanam =
                $monitoringAwal['tanggal_tanam'];

        } else {

            /*
             * Status Baru Ditanam
             * tidak mempunyai monitoring awal.
             */
            $id_turunan = null;

            if ($id_tanah <= 0) {
                throw new Exception(
                    'Tanah wajib dipilih.'
                );
            }

            if ($tanggal_tanam === '') {
                throw new Exception(
                    'Tanggal tanam wajib diisi.'
                );
            }
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
                'id_turunan' =>
                    $id_turunan,

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

                'is_active' =>
                    (int) ($data['is_active'] ?? 1),

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

    public function getMonitoringAwal()
    {
        return $this->model->getMonitoringAwal();
    }

    public function findMonitoringAwal($id)
    {
        return $this->model->findMonitoringAwal($id);
    }

    // public function getBankBenih($id)
    // {
    //     return $this->model->getBankBenih($id);
    // }
}
?>