<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/FiturTambahan.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Roles', 'delete');

secureSessionStart();

$fiturTambahanModel = new FiturTambahan($pdo);

$fiturTambahanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");