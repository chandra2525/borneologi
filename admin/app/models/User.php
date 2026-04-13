<?php

class User
{

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByUsername($username)
    {
        $sql = "SELECT * FROM t_users
        WHERE username = :username
        AND deleted_at IS NULL AND is_active = 1
        LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "username" => $username
        ]);

        return $stmt->fetch();
    }

    public function updateLoginSuccess($id)
    {
        $sql = "UPDATE t_users 
        SET failed_login_count=0,
        last_login_at=NOW()
        WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            "id" => $id
        ]);
    }

    public function increaseLoginFail($id)
    {
        $sql = "UPDATE t_users 
        SET failed_login_count=failed_login_count+1
        WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            "id" => $id
        ]);
    }

    public function lockUser($id)
    {
        $sql = "UPDATE t_users
        SET locked_until = DATE_ADD(NOW(),INTERVAL 15 MINUTE)
        WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            "id" => $id
        ]);
    }

    public function getAll()
    {
        $sql = "SELECT u.*, r.nama as role_name
            FROM t_users u
            LEFT JOIN m_roles r ON r.id=u.id_role
            WHERE u.deleted_at IS NULL
            ORDER BY u.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_users
            WHERE id = :id
            AND deleted_at IS NULL
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
    }

    public function getRoles()
    {
        $sql = "SELECT id,nama FROM m_roles 
                WHERE deleted_at IS NULL
                ORDER BY nama";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function create($data)
    {

        $sql = "INSERT INTO t_users
            (id_role,username,email,password_hash,nama_lengkap,nomor_hp,is_active,created_by)
            VALUES
            (:id_role,:username,:email,:password_hash,:nama_lengkap,:nomor_hp,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        if(!empty($data['password_hash'])){
            $sql = "UPDATE t_users
                SET
                id_role=:id_role,
                username=:username,
                email=:email,
                password_hash=:password_hash,
                nama_lengkap=:nama_lengkap,
                nomor_hp=:nomor_hp,
                is_active=:is_active,
                updated_by=:updated_by
                WHERE id=:id";
        } else {
            $sql = "UPDATE t_users
                SET
                id_role=:id_role,
                username=:username,
                email=:email,
                nama_lengkap=:nama_lengkap,
                nomor_hp=:nomor_hp,
                is_active=:is_active,
                updated_by=:updated_by
                WHERE id=:id";
        }

        $stmt = $this->pdo->prepare($sql);
        $data["id"] = $id;
        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE t_users
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);
    }

}