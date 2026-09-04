<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/PenggunaanPertanian.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Penggunaan Pertanian', 'delete');

$penggunaanPertanianModel = new PenggunaanPertanian($pdo);

$penggunaanPertanianModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");