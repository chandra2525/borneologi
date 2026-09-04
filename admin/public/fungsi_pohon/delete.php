<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/FungsiPohon.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Fungsi Pohon', 'delete');

$fungsiPohonModel = new FungsiPohon($pdo);

$fungsiPohonModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");