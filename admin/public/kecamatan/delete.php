<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Kecamatan.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Kecamatan', 'delete');

$kecamatanModel = new Kecamatan($pdo);
$kecamatanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");