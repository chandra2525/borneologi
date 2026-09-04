<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/User.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Pengguna', 'delete');

$userModel = new User($pdo);

$userModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location:index.php?success=deleted");