<?php

class ActivityLog
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($data)
    {
        $sql = "INSERT INTO t_activity_logs
                (
                    user_id,
                    username,
                    activity,
                    menu_name,
                    target_id,
                    resource,
                    old_data,
                    new_data,
                    description,
                    status,
                    ip_address,
                    user_agent,
                    request_id
                )
                VALUES
                (
                    :user_id,
                    :username,
                    :activity,
                    :menu_name,
                    :target_id,
                    :resource,
                    :old_data,
                    :new_data,
                    :description,
                    :status,
                    :ip_address,
                    :user_agent,
                    :request_id
                )";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':user_id'     => $data['user_id'] ?? null,
            ':username'    => $data['username'] ?? null,
            ':activity'    => $data['activity'] ?? null,
            ':menu_name'   => $data['menu_name'] ?? null,
            ':target_id'   => $data['target_id'] ?? null,
            ':resource'    => $data['resource'] ?? null,
            ':old_data'    => $data['old_data'] ?? null,
            ':new_data'    => $data['new_data'] ?? null,
            ':description' => $data['description'] ?? null,
            ':status'      => $data['status'] ?? 'SUCCESS',
            ':ip_address'  => $data['ip_address'] ?? null,
            ':user_agent'  => $data['user_agent'] ?? null,
            ':request_id'  => $data['request_id'] ?? null
        ]);
    }

    public function getAll($limit = 100)
    {
        $sql = "SELECT
                    l.*,
                    u.nama_lengkap
                FROM t_activity_logs l
                LEFT JOIN t_users u
                    ON u.id = l.user_id
                ORDER BY l.created_at DESC
                LIMIT :limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $sql = "SELECT
                    l.*,
                    u.nama_lengkap
                FROM t_activity_logs l
                LEFT JOIN t_users u
                    ON u.id = l.user_id
                WHERE l.id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
