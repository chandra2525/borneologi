<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/KecepatanAliran.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Kecepatan Aliran', 'delete');

$kecepatanAliranModel = new KecepatanAliran($pdo);

$kecepatanAliranModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");