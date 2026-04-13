<?php

require "../../app/config/database.php";
require "../../app/models/RoleMenuAccess.php";
require "../../app/helpers/escape.php";

$role_id = $_GET['role_id'];
$model = new RoleMenuAccess($pdo);
$menus = $model->getByRole($role_id);

foreach ($menus as $menu) {
    ?>
    <tr>
        <td><?= htmlspecialchars($menu['menu_name']) ?></td>
        <td>
            <input type="checkbox" name="access[<?= $menu['menu_id'] ?>][view]" value="1" <?= $menu['can_view'] ? 'checked' : '' ?>>
        </td>
        <td>
            <input type="checkbox" name="access[<?= $menu['menu_id'] ?>][create]" value="1" <?= $menu['can_create'] ? 'checked' : '' ?>>
        </td>
        <td>
            <input type="checkbox" name="access[<?= $menu['menu_id'] ?>][update]" value="1" <?= $menu['can_update'] ? 'checked' : '' ?>>
        </td>
        <td>
            <input type="checkbox" name="access[<?= $menu['menu_id'] ?>][delete]" value="1" <?= $menu['can_delete'] ? 'checked' : '' ?>>
        </td>
        <td>
            <input type="checkbox" name="access[<?= $menu['menu_id'] ?>][approve]" value="1" <?= $menu['can_approve'] ? 'checked' : '' ?>>
        </td>
        <td>
            <input type="checkbox" name="access[<?= $menu['menu_id'] ?>][export]" value="1" <?= $menu['can_export'] ? 'checked' : '' ?>>
        </td>
    </tr>
<?php } ?>