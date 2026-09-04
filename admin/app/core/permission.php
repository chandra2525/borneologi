<?php

class Permission
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Ambil role ID user yang sedang login.
     */
    private function getRoleId(): ?int
    {
        return isset($_SESSION['role'])
            ? (int) $_SESSION['role']
            : null;
    }

    /**
     * Cek permission berdasarkan kode menu.
     *
     * Contoh:
     * Permission::can($pdo, 'petani', 'view');
     * Permission::can($pdo, 'petani', 'create');
     */
    public static function can(
        PDO $pdo,
        string $menuCode,
        string $action
    ): bool {

        $roleId = (new self($pdo))->getRoleId();

        if (!$roleId) {
            return false;
        }

        $allowedActions = [
            'view'    => 'can_view',
            'create'  => 'can_create',
            'update'  => 'can_update',
            'delete'  => 'can_delete',
            'approve' => 'can_approve',
            'export'  => 'can_export',
        ];

        if (!isset($allowedActions[$action])) {
            return false;
        }

        $column = $allowedActions[$action];

        $sql = "
            SELECT rma.$column
            FROM m_role_menu_access rma

            INNER JOIN m_menus m
                ON m.id = rma.id_menu

            WHERE rma.id_role = ?
              AND m.nama = ?
              AND m.is_active = 1
              AND m.deleted_at IS NULL
              AND rma.is_active = 1
              AND rma.deleted_at IS NULL

            LIMIT 1
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $roleId,
            $menuCode
        ]);

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Wajib memiliki permission.
     */
    public static function authorize(
        PDO $pdo,
        string $menuCode,
        string $action = 'view'
    ): void {

        if (!self::can($pdo, $menuCode, $action)) {

            http_response_code(403);

            die('
                <h1>403 - Forbidden</h1>
                <p>Anda tidak memiliki hak akses untuk melakukan tindakan ini.</p>
            ');
        }
    }
}