<?php

class DetailMonitoringPenanamanController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model =
            new DetailMonitoringPenanaman($pdo);
    }

    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            throw new Exception(
                'Invalid CSRF Token'
            );
        }

        $id_monitoring =
            (int) ($data['id_monitoring'] ?? 0);

        $id_bank_benih =
            (int) ($data['id_bank_benih'] ?? 0);

        $jumlah_ditanam =
            trim($data['jumlah_ditanam'] ?? '');

        $satuan =
            trim($data['satuan'] ?? '');

        $jumlah_hidup =
            trim($data['jumlah_hidup'] ?? '');

        $jumlah_mati =
            trim($data['jumlah_mati'] ?? '');

        $tinggi_rata2_cm =
            trim($data['tinggi_rata2_cm'] ?? '');

        $diameter_rata2_cm =
            trim($data['diameter_rata2_cm'] ?? '');

        $catatan =
            trim($data['catatan'] ?? '');

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
                'Jumlah hidup + jumlah mati tidak boleh '
                . 'lebih besar dari jumlah ditanam.'
            );
        }

        return $this->model->createWithStock(
            [
                'id_monitoring' => $id_monitoring,
                'id_bank_benih' => $id_bank_benih,
                'jumlah_ditanam' => $jumlah_ditanam,
                'satuan' => $satuan,
                'jumlah_hidup' => $jumlah_hidup,
                'jumlah_mati' => $jumlah_mati,
                'tinggi_rata2_cm' => $tinggi_rata2_cm,
                'diameter_rata2_cm' => $diameter_rata2_cm,
                'catatan' => $catatan
            ],
            $user_id
        );
    }

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
            (int) ($data['id_monitoring'] ?? 0);

        $id_bank_benih =
            (int) ($data['id_bank_benih'] ?? 0);

        $jumlah_ditanam =
            trim($data['jumlah_ditanam'] ?? '');

        $satuan =
            trim($data['satuan'] ?? '');

        $jumlah_hidup =
            trim($data['jumlah_hidup'] ?? '');

        $jumlah_mati =
            trim($data['jumlah_mati'] ?? '');

        $tinggi_rata2_cm =
            trim($data['tinggi_rata2_cm'] ?? '');

        $diameter_rata2_cm =
            trim($data['diameter_rata2_cm'] ?? '');

        $catatan =
            trim($data['catatan'] ?? '');

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
                'Jumlah hidup + jumlah mati tidak boleh '
                . 'lebih besar dari jumlah ditanam.'
            );
        }

        return $this->model->updateWithStock(
            $id,
            [
                'id_monitoring' => $id_monitoring,
                'id_bank_benih' => $id_bank_benih,
                'jumlah_ditanam' => $jumlah_ditanam,
                'satuan' => $satuan,
                'jumlah_hidup' => $jumlah_hidup,
                'jumlah_mati' => $jumlah_mati,
                'tinggi_rata2_cm' => $tinggi_rata2_cm,
                'diameter_rata2_cm' => $diameter_rata2_cm,
                'catatan' => $catatan
            ],
            $user_id
        );
    }

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
}