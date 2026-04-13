<?php

require "../../app/config/database.php";
require "../../app/models/Menu.php";

$id = $_GET["id"];

$menuModel = new Menu($pdo);

$menu = $menuModel->findById($id);

$parents = $menuModel->parents();

?>

<form method="POST" action="update.php">

    <input type="hidden" name="id" value="<?= $menu['id'] ?>">

    <label>Nama</label>
    <input name="nama" value="<?= htmlspecialchars($menu['nama']) ?>">

    <br><br>

    <label>Parent</label>

    <select name="id_parent">

        <option value="">Root</option>

        <?php foreach ($parents as $p): ?>

            <option value="<?= $p['id'] ?>" <?= $menu['id_parent'] == $p['id'] ? 'selected' : '' ?>>

                <?= htmlspecialchars($p['nama']) ?>

            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <button type="submit">Update</button>

</form>