<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/KategoriKelompok.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Kategori Kelompok', 'delete');

$kategoriKelompokModel = new KategoriKelompok($pdo);

$kategoriKelompokModel->softDelete($_POST["id"], $_SESSION["user_id"]);

// header("Location: index.php");
header("Location: index.php?success=deleted");