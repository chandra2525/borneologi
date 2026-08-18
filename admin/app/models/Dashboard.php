<?php

class Dashboard
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT
                    (
                        SELECT COUNT(*)
                        FROM t_kaleka
                        WHERE is_active = 1
                        AND deleted_by IS NULL
                    ) AS total_kaleka,

                    (
                        SELECT COUNT(*)
                        FROM t_hutan_adat
                        WHERE is_active = 1
                        AND deleted_by IS NULL
                    ) AS total_hutan_adat,

                    (
                        SELECT COUNT(*)
                        FROM m_desa
                        WHERE is_active = 1
                        AND deleted_by IS NULL
                    ) AS total_desa,

                    (
                        SELECT COUNT(*)
                        FROM m_kecamatan
                        WHERE is_active = 1
                        AND deleted_by IS NULL
                    ) AS total_kecamatan;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
