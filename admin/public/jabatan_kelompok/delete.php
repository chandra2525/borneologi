<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/JabatanKelompok.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Jabatan Kelompok', 'delete');

$jabatanKelompokModel = new JabatanKelompok($pdo);

$jabatanKelompokModel->softDelete($_POST["id"], $_SESSION["user_id"]);

// header("Location: index.php");
header("Location: index.php?success=deleted");