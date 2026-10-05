<?php

class MonitoringPenanamanController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new MonitoringPenanaman($pdo);
    }

    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die('Invalid CSRF Token');
        }

        $id_tanah = (int) ($data['id_tanah'] ?? 0);
        $id_tipe_penanaman = (int) ($data['id_tipe_penanaman'] ?? 0);
        $periode_pengecekan = trim($data['periode_pengecekan'] ?? '');

        $tanggal_tanam = trim($data['tanggal_tanam'] ?? '');
        $tanggal_monitoring = trim($data['tanggal_monitoring'] ?? '');

        $luas_tanam_ha = trim($data['luas_tanam_ha'] ?? '');

        $id_progress_status_monitoring =
            (int) ($data['id_progress_status_monitoring'] ?? 0);

        $catatan = trim($data['catatan'] ?? '');

        if ($id_tanah <= 0) {
            throw new Exception('Tanah wajib dipilih.');
        }

        if ($id_tipe_penanaman <= 0) {
            throw new Exception('Tipe penanaman wajib dipilih.');
        }

        if ($periode_pengecekan === '') {
            throw new Exception('Periode wajib diisi.');
        }

        if ($tanggal_tanam === '') {
            throw new Exception('Tanggal tanam wajib diisi.');
        }

        if ($luas_tanam_ha === '' || (float) $luas_tanam_ha <= 0) {
            throw new Exception('Luas tanam harus lebih dari 0.');
        }

        if ($id_progress_status_monitoring <= 0) {
            throw new Exception('Status monitoring wajib dipilih.');
        }

        if ($tanggal_monitoring !== '') {

            if ($tanggal_monitoring < $tanggal_tanam) {
                throw new Exception(
                    'Tanggal monitoring tidak boleh sebelum tanggal tanam.'
                );
            }
        }

        $kode_monitoring = $this->model->generateKodeMonitoring();

        return $this->model->create([
            'kode_monitoring' => $kode_monitoring,
            'id_tanah' => $id_tanah,
            'id_tipe_penanaman' => $id_tipe_penanaman,
            'periode_pengecekan' => $periode_pengecekan,
            'tanggal_tanam' => $tanggal_tanam,
            'tanggal_monitoring' =>
                $tanggal_monitoring !== ''
                    ? $tanggal_monitoring
                    : null,
            'luas_tanam_ha' => $luas_tanam_ha,
            'id_progress_status_monitoring' =>
                $id_progress_status_monitoring,
            'catatan' =>
                $catatan !== ''
                    ? $catatan
                    : null,
            'created_by' => $user_id
        ]);
    }

    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die('Invalid CSRF Token');
        }

        $id_tanah = (int) ($data['id_tanah'] ?? 0);
        $id_tipe_penanaman = (int) ($data['id_tipe_penanaman'] ?? 0);
        $periode_pengecekan = trim($data['periode_pengecekan'] ?? '');

        $tanggal_tanam = trim($data['tanggal_tanam'] ?? '');
        $tanggal_monitoring = trim($data['tanggal_monitoring'] ?? '');

        $luas_tanam_ha = trim($data['luas_tanam_ha'] ?? '');

        $id_progress_status_monitoring =
            (int) ($data['id_progress_status_monitoring'] ?? 0);

        $catatan = trim($data['catatan'] ?? '');

        if ($id <= 0) {
            throw new Exception('ID monitoring tidak valid.');
        }

        if ($id_tanah <= 0) {
            throw new Exception('Tanah wajib dipilih.');
        }

        if ($id_tipe_penanaman <= 0) {
            throw new Exception('Tipe penanaman wajib dipilih.');
        }

        if ($periode_pengecekan === '') {
            throw new Exception('Periode wajib diisi.');
        }

        if ($tanggal_tanam === '') {
            throw new Exception('Tanggal tanam wajib diisi.');
        }

        if ($luas_tanam_ha === '' || (float) $luas_tanam_ha <= 0) {
            throw new Exception('Luas tanam harus lebih dari 0.');
        }

        if ($id_progress_status_monitoring <= 0) {
            throw new Exception('Status monitoring wajib dipilih.');
        }

        if (
            $tanggal_monitoring !== ''
            && $tanggal_monitoring < $tanggal_tanam
        ) {
            throw new Exception(
                'Tanggal monitoring tidak boleh sebelum tanggal tanam.'
            );
        }

        return $this->model->update($id, [
            'id_tanah' => $id_tanah,
            'id_tipe_penanaman' => $id_tipe_penanaman,
            'periode_pengecekan' => $periode_pengecekan,
            'tanggal_tanam' => $tanggal_tanam,
            'tanggal_monitoring' =>
                $tanggal_monitoring !== ''
                    ? $tanggal_monitoring
                    : null,
            'luas_tanam_ha' => $luas_tanam_ha,
            'id_progress_status_monitoring' =>
                $id_progress_status_monitoring,
            'catatan' =>
                $catatan !== ''
                    ? $catatan
                    : null,
            'updated_by' => $user_id
        ]);
    }

    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die('Invalid CSRF Token');
        }

        if ($id <= 0) {
            throw new Exception('ID monitoring tidak valid.');
        }

        return $this->model->softDelete($id, $user_id);
    }
}