<!-- <?php

require "../../app/config/database.php";
require "../../app/models/RoleMenuAccess.php";
require_once '../../app/core/session.php';
require "../../app/core/csrf.php";
require_once "../../app/core/auth.php";

secureSessionStart();
checkAuth("non_dashboard");

$role_id = $_GET['role_id'];

$model = new RoleMenuAccess($pdo);

$menus = $model->getByRole($role_id);

?>

<form method="POST" action="save_access.php">

    <?= csrfField() ?>

    <input type="hidden" name="role_id" value="<?= $role_id ?>">

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>Menu</th>
                <th>View</th>
                <th>Create</th>
                <th>Update</th>
                <th>Delete</th>
                <th>Approve</th>
                <th>Export</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($menus as $menu): ?>

                <tr>

                    <td><?= htmlspecialchars($menu['menu_name']) ?></td>

                    <td>
                        <input type="checkbox" name="access[<?= $menu['menu_id'] ?>][view]" value="1" <?= $menu['can_view'] ? 'checked' : '' ?>>
                    </td>

                    <td>
                        <input type="checkbox" name="access[<?= $menu['menu_id'] ?>][create]" value="1"
                            <?= $menu['can_create'] ? 'checked' : '' ?>>
                    </td>

                    <td>
                        <input type="checkbox" name="access[<?= $menu['menu_id'] ?>][update]" value="1"
                            <?= $menu['can_update'] ? 'checked' : '' ?>>
                    </td>

                    <td>
                        <input type="checkbox" name="access[<?= $menu['menu_id'] ?>][delete]" value="1"
                            <?= $menu['can_delete'] ? 'checked' : '' ?>>
                    </td>

                    <td>
                        <input type="checkbox" name="access[<?= $menu['menu_id'] ?>][approve]" value="1"
                            <?= $menu['can_approve'] ? 'checked' : '' ?>>
                    </td>

                    <td>
                        <input type="checkbox" name="access[<?= $menu['menu_id'] ?>][export]" value="1"
                            <?= $menu['can_export'] ? 'checked' : '' ?>>
                    </td>

                </tr>

            <?php endforeach ?>

        </tbody>

    </table>

    <button class="btn btn-primary">Save Permission</button>

</form> -->