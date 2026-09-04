<?php
function canView(PDO $pdo, string $menuCode): bool
{
    return Permission::can($pdo, $menuCode, 'view');
}