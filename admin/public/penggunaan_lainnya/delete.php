<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/PenggunaanLainnya.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Penggunaan Lainnya', 'delete');

$penggunaanLainnyaModel = new PenggunaanLainnya($pdo);

$penggunaanLainnyaModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");