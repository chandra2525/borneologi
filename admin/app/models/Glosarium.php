<?php

class Glosarium
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT
                id,
                istilah,
                istilah_lain,
                kategori,
                definisi_singkat,
                penjelasan_lengkap,
                bahasa_asal,
                pengucapan,
                sumber,
                gambar,
                is_active
            FROM t_glosarium
            WHERE deleted_at IS NULL
            ORDER BY id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // public function getAll()
    // {
    //     $sql = "SELECT
    //         id,
    //         istilah,
    //         istilah_lain,
    //         kategori,
    //         definisi_singkat,
    //         penjelasan_lengkap,
    //         bahasa_asal,
    //         pengucapan,
    //         sumber,
    //         gambar,
    //         is_active
    //     FROM t_glosarium
    //     WHERE deleted_at IS NULL
    //     ORDER BY LOWER(istilah) ASC";

    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute();

    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_glosarium
            WHERE id = :id
            AND deleted_at IS NULL
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
    }

    // public function findById($id)
    // {
    //     $sql = "SELECT *
    //     FROM t_glosarium
    //     WHERE id = :id
    //     AND deleted_at IS NULL
    //     LIMIT 1";

    //     $stmt = $this->pdo->prepare($sql);

    //     $stmt->execute([
    //         "id" => $id
    //     ]);

    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }



    public function create($data)
    {

        $sql = "INSERT INTO t_glosarium
            (istilah,istilah_lain,kategori,definisi_singkat,penjelasan_lengkap,bahasa_asal,pengucapan,sumber,gambar,is_active,created_by)
            VALUES
            (:istilah,:istilah_lain,:kategori,:definisi_singkat,:penjelasan_lengkap,:bahasa_asal,:pengucapan,:sumber,:gambar,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        // return $stmt->execute($data);
        if ($stmt->execute($data)) {
            return $this->pdo->lastInsertId();
        }

        return false;
    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_glosarium
            SET
            istilah=:istilah,
            istilah_lain=:istilah_lain,
            kategori=:kategori,
            definisi_singkat=:definisi_singkat,
            penjelasan_lengkap=:penjelasan_lengkap,
            bahasa_asal=:bahasa_asal,
            pengucapan=:pengucapan,
            sumber=:sumber,
            gambar=:gambar,
            is_active=:is_active,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE t_glosarium
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);
    }

    public function getPublicAll()
    {
        $sql = "SELECT
            id,
            istilah,
            istilah_lain,
            kategori,
            definisi_singkat,
            penjelasan_lengkap,
            bahasa_asal,
            pengucapan,
            sumber,
            gambar
        FROM t_glosarium
        WHERE deleted_at IS NULL
        AND is_active = 1
        ORDER BY LOWER(istilah) ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findPublicById($id)
    {
        $sql = "SELECT
            id,
            istilah,
            istilah_lain,
            kategori,
            definisi_singkat,
            penjelasan_lengkap,
            bahasa_asal,
            pengucapan,
            sumber,
            gambar
        FROM t_glosarium
        WHERE id = :id
        AND deleted_at IS NULL
        AND is_active = 1
        LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}