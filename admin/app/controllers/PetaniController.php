<?php

require_once __DIR__ . '/../models/Petani.php';
require_once __DIR__ . '/../core/csrf.php';

class PetaniController
{
    private $model;
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->model = new Petani($pdo);
    }

    public function getPdo()
    {
        return $this->pdo;
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

    public function importExcel($file, $user_id)
    {
        verifyCsrfToken();

        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File Excel gagal diupload.");
        }

        $allowedExtensions = ['xlsx', 'xls'];

        $extension = strtolower(
            pathinfo($file['name'], PATHINFO_EXTENSION)
        );

        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception(
                "Format file tidak diperbolehkan. Gunakan file .xlsx atau .xls."
            );
        }

        require_once __DIR__ . '/../../../vendor/autoload.php';

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile(
            $file['tmp_name']
        );

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($file['tmp_name']);

        $sheet = $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestRow();

        /*
    |--------------------------------------------------------------------------
    | VALIDASI HEADER
    |--------------------------------------------------------------------------
    */

        $expectedHeaders = [
            'NIK',
            'No KK',
            'Nama Lengkap *',
            'Nama Panggilan',
            'Jenis Kelamin *',
            'Tanggal Lahir',
            'No HP',
            'Desa *',
            'Alamat'
        ];

        foreach ($expectedHeaders as $index => $expectedHeader) {

            $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $index + 1
            );

            $actualHeader = trim(
                (string) $sheet->getCell($column . '4')->getValue()
            );

            /*
        | Header bisa saja tidak terbaca tanda bintangnya
        | jika file dibuat/diedit dengan aplikasi Excel tertentu.
        */

            if ($actualHeader !== $expectedHeader) {

                throw new Exception(
                    "Format template tidak sesuai. " .
                        "Header kolom {$column} seharusnya '{$expectedHeader}', " .
                        "tetapi ditemukan '{$actualHeader}'."
                );
            }
        }


        /*
    |--------------------------------------------------------------------------
    | TRANSACTION
    |--------------------------------------------------------------------------
    */

        $pdo = $this->getPdo();

        $pdo->beginTransaction();

        try {

            $success = 0;
            $failed = [];

            /*
        |--------------------------------------------------------------------------
        | BACA DATA MULAI BARIS 5
        |--------------------------------------------------------------------------
        */

            for ($row = 5; $row <= $highestRow; $row++) {

                $nik = trim(
                    (string) $sheet->getCell("A{$row}")->getFormattedValue()
                );

                $no_kk = trim(
                    (string) $sheet->getCell("B{$row}")->getFormattedValue()
                );

                $nama_lengkap = trim(
                    (string) $sheet->getCell("C{$row}")->getValue()
                );

                $nama_panggilan = trim(
                    (string) $sheet->getCell("D{$row}")->getValue()
                );

                $jenis_kelamin = strtoupper(
                    trim(
                        (string) $sheet->getCell("E{$row}")->getValue()
                    )
                );

                $tanggal_lahir = trim(
                    (string) $sheet->getCell("F{$row}")->getFormattedValue()
                );

                $nomor_hp = trim(
                    (string) $sheet->getCell("G{$row}")->getFormattedValue()
                );

                $nama_desa = trim(
                    (string) $sheet->getCell("H{$row}")->getValue()
                );

                $alamat = trim(
                    (string) $sheet->getCell("I{$row}")->getValue()
                );


                /*
            |--------------------------------------------------------------------------
            | BARIS KOSONG
            |--------------------------------------------------------------------------
            */

                if (
                    $nik === '' &&
                    $no_kk === '' &&
                    $nama_lengkap === '' &&
                    $nama_panggilan === '' &&
                    $jenis_kelamin === '' &&
                    $tanggal_lahir === '' &&
                    $nomor_hp === '' &&
                    $nama_desa === '' &&
                    $alamat === ''
                ) {
                    continue;
                }


                /*
            |--------------------------------------------------------------------------
            | VALIDASI WAJIB
            |--------------------------------------------------------------------------
            */

                $errors = [];

                if ($nama_lengkap === '') {
                    $errors[] = 'Nama Lengkap wajib diisi';
                }

                if ($jenis_kelamin === '') {
                    $errors[] = 'Jenis Kelamin wajib diisi';
                }

                if (
                    $jenis_kelamin !== '' &&
                    !in_array($jenis_kelamin, ['L', 'P'])
                ) {

                    $errors[] =
                        'Jenis Kelamin harus L atau P';
                }

                if ($nama_desa === '') {
                    $errors[] = 'Desa wajib diisi';
                }


                /*
            |--------------------------------------------------------------------------
            | VALIDASI NIK
            |--------------------------------------------------------------------------
            */

                if ($nik !== '' && !preg_match('/^\d{16}$/', $nik)) {

                    $errors[] =
                        'NIK harus terdiri dari 16 digit';
                }


                /*
            |--------------------------------------------------------------------------
            | VALIDASI NO KK
            |--------------------------------------------------------------------------
            */

                if ($no_kk !== '' && !preg_match('/^\d{16}$/', $no_kk)) {

                    $errors[] =
                        'No KK harus terdiri dari 16 digit';
                }


                /*
            |--------------------------------------------------------------------------
            | VALIDASI NO HP
            |--------------------------------------------------------------------------
            */

                if ($nomor_hp !== '') {

                    if (!preg_match('/^\d{10,13}$/', $nomor_hp)) {

                        $errors[] =
                            'No HP harus terdiri dari 10-13 digit';
                    }
                }


                /*
            |--------------------------------------------------------------------------
            | CARI DESA
            |--------------------------------------------------------------------------
            */

                $desa = null;

                if ($nama_desa !== '') {

                    $desa = $this->model->getDesaByName(
                        $nama_desa
                    );

                    if (!$desa) {

                        $errors[] =
                            "Desa '{$nama_desa}' tidak ditemukan";
                    }
                }


                /*
            |--------------------------------------------------------------------------
            | JIKA ADA ERROR
            |--------------------------------------------------------------------------
            */

                if (!empty($errors)) {

                    $failed[] = [
                        'row' => $row,
                        'errors' => $errors
                    ];

                    continue;
                }


                /*
            |--------------------------------------------------------------------------
            | NORMALISASI TANGGAL
            |--------------------------------------------------------------------------
            */

                if ($tanggal_lahir === '') {

                    $tanggal_lahir = null;
                } else {

                    $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
                        $sheet->getCell("F{$row}")->getValue()
                    );

                    if ($date) {

                        $tanggal_lahir =
                            $date->format('Y-m-d');
                    }
                }


                /*
            |--------------------------------------------------------------------------
            | INSERT
            |--------------------------------------------------------------------------
            */

                $result = $this->model->create([
                    'nik' => $nik !== '' ? $nik : null,

                    'no_kk' => $no_kk !== ''
                        ? $no_kk
                        : null,

                    'nama_lengkap' =>
                    $nama_lengkap,

                    'nama_panggilan' =>
                    $nama_panggilan !== ''
                        ? $nama_panggilan
                        : null,

                    'jenis_kelamin' =>
                    $jenis_kelamin,

                    'tanggal_lahir' =>
                    $tanggal_lahir,

                    'nomor_hp' =>
                    $nomor_hp !== ''
                        ? $nomor_hp
                        : null,

                    'id_desa' =>
                    $desa['id'],

                    'alamat' =>
                    $alamat !== ''
                        ? $alamat
                        : null,

                    /*
                | Otomatis Aktif
                */
                    'status_petani' =>
                    'aktif',

                    /*
                | Tidak menggunakan foto dari Excel
                */
                    'foto_profil_petani' =>
                    null,

                    /*
                | Otomatis Aktif
                */
                    'is_active' =>
                    1,

                    'created_by' =>
                    $user_id
                ]);

                if ($result) {

                    $success++;
                } else {

                    $failed[] = [
                        'row' => $row,
                        'errors' => [
                            'Gagal menyimpan data ke database'
                        ]
                    ];
                }
            }


            /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */

            $pdo->commit();


            /*
        |--------------------------------------------------------------------------
        | HASIL IMPORT
        |--------------------------------------------------------------------------
        */

            return [
                'success' => $success,
                'failed' => $failed
            ];
        } catch (Exception $e) {

            $pdo->rollBack();

            throw $e;
        }
    }

    public function getDesa()
    {
        return $this->model->getDesa();
    }
}
