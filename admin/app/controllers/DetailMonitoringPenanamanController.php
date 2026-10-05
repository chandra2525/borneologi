<?php

require_once __DIR__ . '/../models/DetailMonitoringPenanaman.php';
require_once __DIR__ . '/../core/csrf.php';

class DetailMonitoringPenanamanController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model =
            new DetailMonitoringPenanaman($pdo);
    }


    public function getByMonitoring($id_monitoring)
    {
        return $this->model->getByMonitoring($id_monitoring);
    }

    public function find($id)
    {
        return $this->model->findById($id);
    }

    /**
     * CREATE Detail Monitoring.
     */
    public function store(
        $data,
        $user_id
    ) {
        // if (!verifyCsrfToken()) {
        //     throw new Exception(
        //         'Invalid CSRF Token'
        //     );
        // }

        $id_monitoring =
            (int) (
                $data['id_monitoring'] ?? 0
            );

        $id_bank_benih =
            (int) (
                $data['id_bank_benih'] ?? 0
            );

        $id_tipe_penanaman =
            (int) (
                $data['id_tipe_penanaman'] ?? 0
            );

        $jumlah_ditanam =
            trim(
                $data['jumlah_ditanam'] ?? ''
            );

        $satuan =
            trim(
                $data['satuan'] ?? ''
            );

        $jumlah_hidup =
            trim(
                $data['jumlah_hidup'] ?? ''
            );

        $jumlah_mati =
            trim(
                $data['jumlah_mati'] ?? ''
            );

        $tinggi_rata2_cm =
            trim(
                $data['tinggi_rata2_cm'] ?? ''
            );

        $diameter_rata2_cm =
            trim(
                $data['diameter_rata2_cm'] ?? ''
            );

        $luas_tanam_ha =
            trim(
                $data['luas_tanam_ha'] ?? ''
            );

        $catatan =
            trim(
                $data['catatan'] ?? ''
            );

        /**
         * Required.
         */
        if ($id_monitoring <= 0) {
            throw new Exception(
                'Monitoring Penanaman wajib dipilih.'
            );
        }

        if ($id_bank_benih <= 0) {
            throw new Exception(
                'Bank Benih wajib dipilih.'
            );
        }

        if ($id_tipe_penanaman <= 0) {
            throw new Exception(
                'Tipe Penanaman wajib dipilih.'
            );
        }

        /**
         * Jumlah ditanam.
         */
        if (
            $jumlah_ditanam === ''
            || !is_numeric($jumlah_ditanam)
            || (float) $jumlah_ditanam <= 0
        ) {
            throw new Exception(
                'Jumlah ditanam harus lebih dari 0.'
            );
        }

        /**
         * Satuan.
         */
        if (
            !in_array(
                $satuan,
                [
                    'butir',
                    'gram',
                    'kg',
                    'paket',
                    'bibit'
                ],
                true
            )
        ) {
            throw new Exception(
                'Satuan tidak valid.'
            );
        }

        /**
         * Luas tanam.
         */
        if (
            $luas_tanam_ha === ''
            || !is_numeric($luas_tanam_ha)
            || (float) $luas_tanam_ha <= 0
        ) {
            throw new Exception(
                'Luas tanam harus lebih dari 0.'
            );
        }

        /**
         * Optional numeric.
         */
        $this->validateOptionalNumber(
            $jumlah_hidup,
            'Jumlah hidup'
        );

        $this->validateOptionalNumber(
            $jumlah_mati,
            'Jumlah mati'
        );

        $this->validateOptionalDecimal(
            $tinggi_rata2_cm,
            'Tinggi rata-rata'
        );

        $this->validateOptionalDecimal(
            $diameter_rata2_cm,
            'Diameter rata-rata'
        );

        /**
         * Validasi hidup + mati.
         */
        if (
            $jumlah_hidup !== ''
            && $jumlah_mati !== ''
            && (
                (float) $jumlah_hidup
                + (float) $jumlah_mati
                > (float) $jumlah_ditanam
            )
        ) {
            throw new Exception(
                'Jumlah hidup + jumlah mati tidak boleh lebih besar dari jumlah ditanam.'
            );
        }

        /**
         * Jumlah hidup tidak boleh lebih
         * dari jumlah ditanam.
         */
        if (
            $jumlah_hidup !== ''
            && (
                (float) $jumlah_hidup
                > (float) $jumlah_ditanam
            )
        ) {
            throw new Exception(
                'Jumlah hidup tidak boleh lebih besar dari jumlah ditanam.'
            );
        }

        return $this->model->createWithStock(
            [
                'id_monitoring' =>
                    $id_monitoring,

                'id_bank_benih' =>
                    $id_bank_benih,

                'id_tipe_penanaman' =>
                    $id_tipe_penanaman,

                'jumlah_ditanam' =>
                    $jumlah_ditanam,

                'satuan' =>
                    $satuan,

                'jumlah_hidup' =>
                    $jumlah_hidup,

                'jumlah_mati' =>
                    $jumlah_mati,

                'tinggi_rata2_cm' =>
                    $tinggi_rata2_cm,

                'diameter_rata2_cm' =>
                    $diameter_rata2_cm,

                'luas_tanam_ha' =>
                    $luas_tanam_ha,

                'catatan' =>
                    $catatan
            ],
            $user_id
        );
    }

    /**
     * UPDATE Detail Monitoring.
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

        $id_monitoring =
            (int) (
                $data['id_monitoring'] ?? 0
            );

        $id_bank_benih =
            (int) (
                $data['id_bank_benih'] ?? 0
            );

        $id_tipe_penanaman =
            (int) (
                $data['id_tipe_penanaman'] ?? 0
            );

        $jumlah_ditanam =
            trim(
                $data['jumlah_ditanam'] ?? ''
            );

        $satuan =
            trim(
                $data['satuan'] ?? ''
            );

        $jumlah_hidup =
            trim(
                $data['jumlah_hidup'] ?? ''
            );

        $jumlah_mati =
            trim(
                $data['jumlah_mati'] ?? ''
            );

        $tinggi_rata2_cm =
            trim(
                $data['tinggi_rata2_cm'] ?? ''
            );

        $diameter_rata2_cm =
            trim(
                $data['diameter_rata2_cm'] ?? ''
            );

        $luas_tanam_ha =
            trim(
                $data['luas_tanam_ha'] ?? ''
            );

        $catatan =
            trim(
                $data['catatan'] ?? ''
            );

        if ($id <= 0) {
            throw new Exception(
                'ID Detail Monitoring tidak valid.'
            );
        }

        if ($id_monitoring <= 0) {
            throw new Exception(
                'Monitoring Penanaman wajib dipilih.'
            );
        }

        if ($id_bank_benih <= 0) {
            throw new Exception(
                'Bank Benih wajib dipilih.'
            );
        }

        if ($id_tipe_penanaman <= 0) {
            throw new Exception(
                'Tipe Penanaman wajib dipilih.'
            );
        }

        if (
            $jumlah_ditanam === ''
            || !is_numeric($jumlah_ditanam)
            || (float) $jumlah_ditanam <= 0
        ) {
            throw new Exception(
                'Jumlah ditanam harus lebih dari 0.'
            );
        }

        if (
            !in_array(
                $satuan,
                [
                    'butir',
                    'gram',
                    'kg',
                    'paket',
                    'bibit'
                ],
                true
            )
        ) {
            throw new Exception(
                'Satuan tidak valid.'
            );
        }

        if (
            $luas_tanam_ha === ''
            || !is_numeric($luas_tanam_ha)
            || (float) $luas_tanam_ha <= 0
        ) {
            throw new Exception(
                'Luas tanam harus lebih dari 0.'
            );
        }

        /**
         * Optional numeric.
         */
        $this->validateOptionalNumber(
            $jumlah_hidup,
            'Jumlah hidup'
        );

        $this->validateOptionalNumber(
            $jumlah_mati,
            'Jumlah mati'
        );

        $this->validateOptionalDecimal(
            $tinggi_rata2_cm,
            'Tinggi rata-rata'
        );

        $this->validateOptionalDecimal(
            $diameter_rata2_cm,
            'Diameter rata-rata'
        );

        /**
         * Validasi hidup + mati.
         */
        if (
            $jumlah_hidup !== ''
            && $jumlah_mati !== ''
            && (
                (float) $jumlah_hidup
                + (float) $jumlah_mati
                > (float) $jumlah_ditanam
            )
        ) {
            throw new Exception(
                'Jumlah hidup + jumlah mati tidak boleh lebih besar dari jumlah ditanam.'
            );
        }

        if (
            $jumlah_hidup !== ''
            && (
                (float) $jumlah_hidup
                > (float) $jumlah_ditanam
            )
        ) {
            throw new Exception(
                'Jumlah hidup tidak boleh lebih besar dari jumlah ditanam.'
            );
        }

        return $this->model->updateWithStock(
            $id,
            [
                'id_monitoring' =>
                    $id_monitoring,

                'id_bank_benih' =>
                    $id_bank_benih,

                'id_tipe_penanaman' =>
                    $id_tipe_penanaman,

                'jumlah_ditanam' =>
                    $jumlah_ditanam,

                'satuan' =>
                    $satuan,

                'jumlah_hidup' =>
                    $jumlah_hidup,

                'jumlah_mati' =>
                    $jumlah_mati,

                'tinggi_rata2_cm' =>
                    $tinggi_rata2_cm,

                'diameter_rata2_cm' =>
                    $diameter_rata2_cm,

                'luas_tanam_ha' =>
                    $luas_tanam_ha,

                'catatan' =>
                    $catatan
            ],
            $user_id
        );
    }

    /**
     * DELETE Detail Monitoring.
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
                'ID Detail Monitoring tidak valid.'
            );
        }

        return $this->model->deleteWithStock(
            $id,
            $user_id
        );
    }

    /**
     * Validasi angka optional.
     */
    private function validateOptionalNumber(
        $value,
        $label
    ) {
        if (
            $value !== ''
            && (
                !is_numeric($value)
                || (float) $value < 0
            )
        ) {
            throw new Exception(
                $label . ' tidak valid.'
            );
        }
    }

    /**
     * Validasi decimal optional.
     */
    private function validateOptionalDecimal(
        $value,
        $label
    ) {
        if (
            $value !== ''
            && (
                !is_numeric($value)
                || (float) $value < 0
            )
        ) {
            throw new Exception(
                $label . ' tidak valid.'
            );
        }
    }

    public function getBankBenih($id = null)
    {
        return $this->model->getBankBenih($id);
    }


    public function getMonitoringPenanaman($id = null)
    {
        return $this->model->getMonitoringPenanaman($id);
    }


    public function getTipePenanaman()
    {
        return $this->model->getTipePenanaman();
    }

    public function existsPair($id_monitoring, $id_bank_benih)
    {
        return $this->model->existsPair($id_monitoring, $id_bank_benih);
    }
}
?>