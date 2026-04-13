<?php

require "../../app/config/database.php";
require "../../app/models/Menu.php";

$menuModel = new Menu($pdo);

$parents = $menuModel->parents();

?>

<form method="POST" action="store.php">

    <label>Kode</label>
    <input name="kode">

    <br><br>

    <label>Nama</label>
    <input name="nama">

    <br><br>

    <label>Path</label>
    <input name="path">

    <br><br>

    <label>Icon</label>
    <input name="icon">

    <br><br>

    <label>Parent Menu</label>

    <select name="id_parent">

        <option value="">Root Menu</option>

        <?php foreach ($parents as $p): ?>

            <option value="<?= $p['id'] ?>">

                <?= htmlspecialchars($p['nama']) ?>

            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <label>Urutan</label>
    <input name="urutan" value="0">

    <br><br>

    <label>Status</label>

    <input type="radio" name="is_active" value="1" checked>Aktif
    <input type="radio" name="is_active" value="0">Nonaktif

    <br><br>

    <button type="submit">Simpan</button>

</form>