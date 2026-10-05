<?php

class DetailMonitoringPenanaman
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * =========================================================
     * GET ALL
     * =========================================================
     */
    public function getAll()
    {
        $sql = "SELECT
                    dm.id,
                    dm.id_monitoring,
                    dm.id_bank_benih,
                    dm.jumlah_ditanam,
                    dm.satuan,
                    dm.jumlah_hidup,
                    dm.jumlah_mati,
                    dm.tinggi_rata2_cm,
                    dm.diameter_rata2_cm,
                    dm.catatan,
                    bb.nomor_aksesi,
                    bb.nama_lokal AS nama_bank_benih,
                    bb.nama_ilmiah,
                    mp.kode_monitoring
                FROM t_detail_monitoring_penanaman dm

                LEFT JOIN t_bank_benih bb
                    ON bb.id = dm.id_bank_benih

                LEFT JOIN t_monitoring_penanaman mp
                    ON mp.id = dm.id_monitoring

                WHERE dm.deleted_at IS NULL

                ORDER BY dm.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }


    /**
     * =========================================================
     * FIND BY ID
     * =========================================================
     */
    public function findById($id)
    {
        $sql = "SELECT
                    dm.*,

                    bb.nomor_aksesi,
                    bb.nama_lokal AS nama_bank_benih,
                    bb.nama_ilmiah,
                    bb.satuan_stok,
                    bb.jumlah_stok,

                    mp.kode_monitoring

                FROM t_detail_monitoring_penanaman dm

                LEFT JOIN t_bank_benih bb
                    ON bb.id = dm.id_bank_benih

                LEFT JOIN t_monitoring_penanaman mp
                    ON mp.id = dm.id_monitoring

                WHERE dm.id = :id
                  AND dm.deleted_at IS NULL

                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
    }


    /**
     * =========================================================
     * CREATE DETAIL + KURANGI STOK + MUTASI KELUAR
     *
     * Semua dilakukan dalam 1 transaction.
     * =========================================================
     */
    public function createWithStock($data, $userId)
    {
        try {

            $this->pdo->beginTransaction();


            /*
             * -------------------------------------------------
             * 1. LOCK BANK BENIH
             * -------------------------------------------------
             */
            $sqlBank = "SELECT
                            id,
                            nomor_aksesi,
                            nama_lokal,
                            jumlah_stok,
                            satuan_stok,
                            is_active
                        FROM t_bank_benih
                        WHERE id = :id
                          AND deleted_at IS NULL
                        LIMIT 1
                        FOR UPDATE";

            $stmtBank = $this->pdo->prepare($sqlBank);

            $stmtBank->execute([
                "id" => $data["id_bank_benih"]
            ]);

            $bank = $stmtBank->fetch();

            if (!$bank) {
                throw new Exception("Bank Benih tidak ditemukan.");
            }


            /*
             * -------------------------------------------------
             * 2. CEK BANK BENIH AKTIF
             * -------------------------------------------------
             */
            if ((int)$bank["is_active"] !== 1) {
                throw new Exception("Bank Benih sedang tidak aktif.");
            }


            /*
             * -------------------------------------------------
             * 3. VALIDASI SATUAN
             * -------------------------------------------------
             */
            if ($bank["satuan_stok"] !== $data["satuan"]) {

                throw new Exception(
                    "Satuan tidak sesuai. Stok Bank Benih tersedia dalam satuan "
                    . $bank["satuan_stok"] . "."
                );
            }


            /*
             * -------------------------------------------------
             * 4. VALIDASI JUMLAH
             * -------------------------------------------------
             */
            $jumlah = (float)$data["jumlah_ditanam"];

            if ($jumlah <= 0) {
                throw new Exception(
                    "Jumlah ditanam harus lebih besar dari 0."
                );
            }


            /*
             * -------------------------------------------------
             * 5. CEK STOK
             * -------------------------------------------------
             */
            $stokSebelum = (float)$bank["jumlah_stok"];

            if ($jumlah > $stokSebelum) {

                throw new Exception(
                    "Stok tidak mencukupi. Stok tersedia: "
                    . number_format($stokSebelum, 2, ".", "")
                    . " "
                    . $bank["satuan_stok"]
                    . ", jumlah ditanam: "
                    . number_format($jumlah, 2, ".", "")
                    . " "
                    . $data["satuan"]
                    . "."
                );
            }


            /*
             * -------------------------------------------------
             * 6. HITUNG STOK SESUDAH
             * -------------------------------------------------
             */
            $stokSesudah = $stokSebelum - $jumlah;

            /*
             * Hindari angka negatif akibat floating point.
             */
            if ($stokSesudah < 0 && $stokSesudah > -0.00001) {
                $stokSesudah = 0;
            }

            if ($stokSesudah < 0) {
                throw new Exception("Stok tidak boleh menjadi negatif.");
            }


            /*
             * -------------------------------------------------
             * 7. INSERT DETAIL MONITORING
             * -------------------------------------------------
             */
            $sqlDetail = "INSERT INTO t_detail_monitoring_penanaman
                (
                    id_monitoring,
                    id_bank_benih,
                    jumlah_ditanam,
                    satuan,
                    jumlah_hidup,
                    jumlah_mati,
                    tinggi_rata2_cm,
                    diameter_rata2_cm,
                    catatan,
                    created_by
                )
                VALUES
                (
                    :id_monitoring,
                    :id_bank_benih,
                    :jumlah_ditanam,
                    :satuan,
                    :jumlah_hidup,
                    :jumlah_mati,
                    :tinggi_rata2_cm,
                    :diameter_rata2_cm,
                    :catatan,
                    :created_by
                )";

            $stmtDetail = $this->pdo->prepare($sqlDetail);

            $stmtDetail->execute([
                "id_monitoring" => $data["id_monitoring"],
                "id_bank_benih" => $data["id_bank_benih"],
                "jumlah_ditanam" => $jumlah,
                "satuan" => $data["satuan"],
                "jumlah_hidup" => $data["jumlah_hidup"],
                "jumlah_mati" => $data["jumlah_mati"],
                "tinggi_rata2_cm" => $data["tinggi_rata2_cm"],
                "diameter_rata2_cm" => $data["diameter_rata2_cm"],
                "catatan" => $data["catatan"],
                "created_by" => $userId
            ]);

            $detailId = $this->pdo->lastInsertId();


            /*
             * -------------------------------------------------
             * 8. UPDATE STOK BANK BENIH
             * -------------------------------------------------
             */
            $sqlUpdateStock = "UPDATE t_bank_benih
                SET
                    jumlah_stok = :jumlah_stok,
                    updated_by = :updated_by
                WHERE id = :id";

            $stmtUpdateStock = $this->pdo->prepare($sqlUpdateStock);

            $stmtUpdateStock->execute([
                "jumlah_stok" => number_format(
                    $stokSesudah,
                    2,
                    ".",
                    ""
                ),
                "updated_by" => $userId,
                "id" => $data["id_bank_benih"]
            ]);


            /*
             * -------------------------------------------------
             * 9. INSERT MUTASI KELUAR
             * -------------------------------------------------
             */
            $sqlMutasi = "INSERT INTO t_mutasi_stok_benih
                (
                    id_bank_benih,
                    tipe_mutasi,
                    arah_koreksi,
                    jumlah,
                    stok_sebelum,
                    stok_sesudah,
                    satuan,
                    sumber,
                    id_referensi,
                    alasan,
                    tanggal_mutasi,
                    created_by
                )
                VALUES
                (
                    :id_bank_benih,
                    'KELUAR',
                    NULL,
                    :jumlah,
                    :stok_sebelum,
                    :stok_sesudah,
                    :satuan,
                    'DETAIL_MONITORING',
                    :id_referensi,
                    :alasan,
                    NOW(),
                    :created_by
                )";

            $stmtMutasi = $this->pdo->prepare($sqlMutasi);

            $stmtMutasi->execute([
                "id_bank_benih" => $data["id_bank_benih"],
                "jumlah" => $jumlah,
                "stok_sebelum" => $stokSebelum,
                "stok_sesudah" => $stokSesudah,
                "satuan" => $data["satuan"],
                "id_referensi" => $detailId,
                "alasan" => "Penanaman melalui Detail Monitoring Penanaman",
                "created_by" => $userId
            ]);

            $mutasiId = $this->pdo->lastInsertId();


            /*
             * -------------------------------------------------
             * 10. COMMIT
             * -------------------------------------------------
             */
            $this->pdo->commit();


            return [
                "success" => true,
                "id" => $detailId,
                "id_mutasi" => $mutasiId,
                "stok_sebelum" => $stokSebelum,
                "stok_sesudah" => $stokSesudah,
                "jumlah" => $jumlah,
                "satuan" => $data["satuan"]
            ];


        } catch (Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
    }


    /**
     * =========================================================
     * UPDATE DETAIL + PENYESUAIAN STOK
     *
     * Jika jumlah bertambah:
     * stok berkurang sebesar delta.
     *
     * Jika jumlah berkurang:
     * stok dikembalikan sebesar delta.
     * =========================================================
     */
    public function updateWithStock($id, $data, $userId)
    {
        try {

            $this->pdo->beginTransaction();


            /*
             * -------------------------------------------------
             * 1. LOCK DETAIL LAMA
             * -------------------------------------------------
             */
            $sqlOld = "SELECT *
                       FROM t_detail_monitoring_penanaman
                       WHERE id = :id
                         AND deleted_at IS NULL
                       LIMIT 1
                       FOR UPDATE";

            $stmtOld = $this->pdo->prepare($sqlOld);

            $stmtOld->execute([
                "id" => $id
            ]);

            $old = $stmtOld->fetch();

            if (!$old) {
                throw new Exception(
                    "Detail Monitoring Penanaman tidak ditemukan."
                );
            }


            /*
             * -------------------------------------------------
             * 2. CEK DUPLIKASI MONITORING + BANK BENIH
             * -------------------------------------------------
             */
            $sqlDuplicate = "SELECT id
                             FROM t_detail_monitoring_penanaman
                             WHERE id_monitoring = :id_monitoring
                               AND id_bank_benih = :id_bank_benih
                               AND id <> :id
                               AND deleted_at IS NULL
                             LIMIT 1";

            $stmtDuplicate = $this->pdo->prepare($sqlDuplicate);

            $stmtDuplicate->execute([
                "id_monitoring" => $data["id_monitoring"],
                "id_bank_benih" => $data["id_bank_benih"],
                "id" => $id
            ]);

            if ($stmtDuplicate->fetch()) {
                throw new Exception(
                    "Kombinasi Monitoring dan Bank Benih tersebut sudah digunakan."
                );
            }


            $jumlahLama = (float)$old["jumlah_ditanam"];
            $jumlahBaru = (float)$data["jumlah_ditanam"];


            if ($jumlahBaru <= 0) {
                throw new Exception(
                    "Jumlah ditanam harus lebih besar dari 0."
                );
            }


            /*
             * -------------------------------------------------
             * 3. KASUS BANK BENIH TIDAK BERUBAH
             * -------------------------------------------------
             */
            if (
                (int)$old["id_bank_benih"] ===
                (int)$data["id_bank_benih"]
            ) {

                /*
                 * Lock bank benih.
                 */
                $sqlBank = "SELECT
                                id,
                                nomor_aksesi,
                                nama_lokal,
                                jumlah_stok,
                                satuan_stok,
                                is_active
                            FROM t_bank_benih
                            WHERE id = :id
                              AND deleted_at IS NULL
                            LIMIT 1
                            FOR UPDATE";

                $stmtBank = $this->pdo->prepare($sqlBank);

                $stmtBank->execute([
                    "id" => $data["id_bank_benih"]
                ]);

                $bank = $stmtBank->fetch();

                if (!$bank) {
                    throw new Exception("Bank Benih tidak ditemukan.");
                }


                if ((int)$bank["is_active"] !== 1) {
                    throw new Exception("Bank Benih sedang tidak aktif.");
                }


                if ($bank["satuan_stok"] !== $data["satuan"]) {
                    throw new Exception(
                        "Satuan tidak sesuai dengan satuan stok Bank Benih."
                    );
                }


                /*
                 * Delta:
                 *
                 * + = membutuhkan stok tambahan
                 * - = stok dikembalikan
                 */
                $delta = $jumlahBaru - $jumlahLama;

                $stokSebelum = (float)$bank["jumlah_stok"];
                $stokSesudah = $stokSebelum;


                /*
                 * JUMLAH BERTAMBAH
                 */
                if ($delta > 0) {

                    if ($delta > $stokSebelum) {
                        throw new Exception(
                            "Stok tidak mencukupi untuk perubahan jumlah tanam."
                        );
                    }

                    $stokSesudah = $stokSebelum - $delta;


                    $sqlMutasi = "INSERT INTO t_mutasi_stok_benih
                        (
                            id_bank_benih,
                            tipe_mutasi,
                            arah_koreksi,
                            jumlah,
                            stok_sebelum,
                            stok_sesudah,
                            satuan,
                            sumber,
                            id_referensi,
                            alasan,
                            tanggal_mutasi,
                            created_by
                        )
                        VALUES
                        (
                            :id_bank_benih,
                            'KELUAR',
                            NULL,
                            :jumlah,
                            :stok_sebelum,
                            :stok_sesudah,
                            :satuan,
                            'DETAIL_MONITORING_EDIT',
                            :id_referensi,
                            :alasan,
                            NOW(),
                            :created_by
                        )";

                    $stmtMutasi = $this->pdo->prepare($sqlMutasi);

                    $stmtMutasi->execute([
                        "id_bank_benih" => $data["id_bank_benih"],
                        "jumlah" => $delta,
                        "stok_sebelum" => $stokSebelum,
                        "stok_sesudah" => $stokSesudah,
                        "satuan" => $data["satuan"],
                        "id_referensi" => $id,
                        "alasan" => "Penambahan jumlah tanam melalui edit Detail Monitoring",
                        "created_by" => $userId
                    ]);
                }


                /*
                 * JUMLAH BERKURANG
                 */
                elseif ($delta < 0) {

                    $dikembalikan = abs($delta);

                    $stokSesudah = $stokSebelum + $dikembalikan;


                    $sqlMutasi = "INSERT INTO t_mutasi_stok_benih
                        (
                            id_bank_benih,
                            tipe_mutasi,
                            arah_koreksi,
                            jumlah,
                            stok_sebelum,
                            stok_sesudah,
                            satuan,
                            sumber,
                            id_referensi,
                            alasan,
                            tanggal_mutasi,
                            created_by
                        )
                        VALUES
                        (
                            :id_bank_benih,
                            'KOREKSI',
                            'TAMBAH',
                            :jumlah,
                            :stok_sebelum,
                            :stok_sesudah,
                            :satuan,
                            'DETAIL_MONITORING_EDIT',
                            :id_referensi,
                            :alasan,
                            NOW(),
                            :created_by
                        )";

                    $stmtMutasi = $this->pdo->prepare($sqlMutasi);

                    $stmtMutasi->execute([
                        "id_bank_benih" => $data["id_bank_benih"],
                        "jumlah" => $dikembalikan,
                        "stok_sebelum" => $stokSebelum,
                        "stok_sesudah" => $stokSesudah,
                        "satuan" => $data["satuan"],
                        "id_referensi" => $id,
                        "alasan" => "Pengembalian selisih jumlah tanam melalui edit Detail Monitoring",
                        "created_by" => $userId
                    ]);
                }


                /*
                 * UPDATE STOK
                 */
                if ($delta != 0) {

                    $sqlUpdateStock = "UPDATE t_bank_benih
                        SET
                            jumlah_stok = :jumlah_stok,
                            updated_by = :updated_by
                        WHERE id = :id";

                    $stmtUpdateStock = $this->pdo->prepare($sqlUpdateStock);

                    $stmtUpdateStock->execute([
                        "jumlah_stok" => number_format(
                            $stokSesudah,
                            2,
                            ".",
                            ""
                        ),
                        "updated_by" => $userId,
                        "id" => $data["id_bank_benih"]
                    ]);
                }
            }


            /*
             * -------------------------------------------------
             * 4. UPDATE DETAIL
             * -------------------------------------------------
             */
            $sqlUpdate = "UPDATE t_detail_monitoring_penanaman
                SET
                    id_monitoring = :id_monitoring,
                    id_bank_benih = :id_bank_benih,
                    jumlah_ditanam = :jumlah_ditanam,
                    satuan = :satuan,
                    jumlah_hidup = :jumlah_hidup,
                    jumlah_mati = :jumlah_mati,
                    tinggi_rata2_cm = :tinggi_rata2_cm,
                    diameter_rata2_cm = :diameter_rata2_cm,
                    catatan = :catatan,
                    updated_by = :updated_by
                WHERE id = :id";

            $stmtUpdate = $this->pdo->prepare($sqlUpdate);

            $stmtUpdate->execute([
                "id_monitoring" => $data["id_monitoring"],
                "id_bank_benih" => $data["id_bank_benih"],
                "jumlah_ditanam" => $jumlahBaru,
                "satuan" => $data["satuan"],
                "jumlah_hidup" => $data["jumlah_hidup"],
                "jumlah_mati" => $data["jumlah_mati"],
                "tinggi_rata2_cm" => $data["tinggi_rata2_cm"],
                "diameter_rata2_cm" => $data["diameter_rata2_cm"],
                "catatan" => $data["catatan"],
                "updated_by" => $userId,
                "id" => $id
            ]);


            $this->pdo->commit();


            return [
                "success" => true,
                "id" => $id,
                "jumlah_lama" => $jumlahLama,
                "jumlah_baru" => $jumlahBaru
            ];


        } catch (Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
    }


    /**
     * =========================================================
     * DELETE DETAIL + KEMBALIKAN STOK
     * =========================================================
     */
    public function deleteWithStock($id, $userId)
    {
        try {

            $this->pdo->beginTransaction();


            /*
             * -------------------------------------------------
             * 1. LOCK DETAIL
             * -------------------------------------------------
             */
            $sqlDetail = "SELECT *
                          FROM t_detail_monitoring_penanaman
                          WHERE id = :id
                            AND deleted_at IS NULL
                          LIMIT 1
                          FOR UPDATE";

            $stmtDetail = $this->pdo->prepare($sqlDetail);

            $stmtDetail->execute([
                "id" => $id
            ]);

            $detail = $stmtDetail->fetch();

            if (!$detail) {
                throw new Exception(
                    "Detail Monitoring Penanaman tidak ditemukan."
                );
            }


            $jumlah = (float)$detail["jumlah_ditanam"];


            /*
             * -------------------------------------------------
             * 2. LOCK BANK BENIH
             * -------------------------------------------------
             */
            $sqlBank = "SELECT
                            id,
                            jumlah_stok,
                            satuan_stok
                        FROM t_bank_benih
                        WHERE id = :id
                          AND deleted_at IS NULL
                        LIMIT 1
                        FOR UPDATE";

            $stmtBank = $this->pdo->prepare($sqlBank);

            $stmtBank->execute([
                "id" => $detail["id_bank_benih"]
            ]);

            $bank = $stmtBank->fetch();

            if (!$bank) {
                throw new Exception(
                    "Bank Benih terkait tidak ditemukan."
                );
            }


            /*
             * -------------------------------------------------
             * 3. VALIDASI SATUAN
             * -------------------------------------------------
             */
            if ($bank["satuan_stok"] !== $detail["satuan"]) {
                throw new Exception(
                    "Satuan detail tidak sesuai dengan satuan Bank Benih."
                );
            }


            $stokSebelum = (float)$bank["jumlah_stok"];
            $stokSesudah = $stokSebelum + $jumlah;


            /*
             * -------------------------------------------------
             * 4. UPDATE STOK
             * -------------------------------------------------
             */
            $sqlUpdateStock = "UPDATE t_bank_benih
                SET
                    jumlah_stok = :jumlah_stok,
                    updated_by = :updated_by
                WHERE id = :id";

            $stmtUpdateStock = $this->pdo->prepare($sqlUpdateStock);

            $stmtUpdateStock->execute([
                "jumlah_stok" => number_format(
                    $stokSesudah,
                    2,
                    ".",
                    ""
                ),
                "updated_by" => $userId,
                "id" => $detail["id_bank_benih"]
            ]);


            /*
             * -------------------------------------------------
             * 5. INSERT MUTASI PENGEMBALIAN
             * -------------------------------------------------
             */
            $sqlMutasi = "INSERT INTO t_mutasi_stok_benih
                (
                    id_bank_benih,
                    tipe_mutasi,
                    arah_koreksi,
                    jumlah,
                    stok_sebelum,
                    stok_sesudah,
                    satuan,
                    sumber,
                    id_referensi,
                    alasan,
                    tanggal_mutasi,
                    created_by
                )
                VALUES
                (
                    :id_bank_benih,
                    'KOREKSI',
                    'TAMBAH',
                    :jumlah,
                    :stok_sebelum,
                    :stok_sesudah,
                    :satuan,
                    'DETAIL_MONITORING_DELETE',
                    :id_referensi,
                    :alasan,
                    NOW(),
                    :created_by
                )";

            $stmtMutasi = $this->pdo->prepare($sqlMutasi);

            $stmtMutasi->execute([
                "id_bank_benih" => $detail["id_bank_benih"],
                "jumlah" => $jumlah,
                "stok_sebelum" => $stokSebelum,
                "stok_sesudah" => $stokSesudah,
                "satuan" => $detail["satuan"],
                "id_referensi" => $id,
                "alasan" => "Pengembalian stok karena Detail Monitoring Penanaman dihapus",
                "created_by" => $userId
            ]);


            /*
             * -------------------------------------------------
             * 6. SOFT DELETE DETAIL
             * -------------------------------------------------
             */
            $sqlDelete = "UPDATE t_detail_monitoring_penanaman
                SET
                    deleted_at = NOW(),
                    deleted_by = :deleted_by
                WHERE id = :id";

            $stmtDelete = $this->pdo->prepare($sqlDelete);

            $stmtDelete->execute([
                "deleted_by" => $userId,
                "id" => $id
            ]);


            /*
             * -------------------------------------------------
             * 7. COMMIT
             * -------------------------------------------------
             */
            $this->pdo->commit();


            return [
                "success" => true,
                "id" => $id,
                "jumlah_dikembalikan" => $jumlah,
                "stok_sebelum" => $stokSebelum,
                "stok_sesudah" => $stokSesudah
            ];


        } catch (Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
    }


    /**
     * =========================================================
     * BANK BENIH
     * =========================================================
     */
    public function getBankBenih()
    {
        $sql = "SELECT
                    id,
                    nomor_aksesi,
                    nama_lokal,
                    nama_ilmiah,
                    jumlah_stok,
                    satuan_stok
                FROM t_bank_benih
                WHERE deleted_at IS NULL
                  AND is_active = 1
                ORDER BY nama_lokal ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }


    /**
     * =========================================================
     * MONITORING PENANAMAN
     * =========================================================
     */
    public function getMonitoringPenanaman()
    {
        $sql = "SELECT
                    id,
                    kode_monitoring
                FROM t_monitoring_penanaman
                WHERE deleted_at IS NULL
                ORDER BY id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }


    /**
     * =========================================================
     * GET DETAIL + STOCK INFO
     * =========================================================
     */
    public function getBankBenihById($id)
    {
        $sql = "SELECT
                    id,
                    nomor_aksesi,
                    nama_lokal,
                    nama_ilmiah,
                    jumlah_stok,
                    satuan_stok,
                    is_active
                FROM t_bank_benih
                WHERE id = :id
                  AND deleted_at IS NULL
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
    }
}