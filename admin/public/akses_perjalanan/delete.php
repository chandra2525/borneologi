<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/AksesPerjalanan.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Akses Perjalanan', 'delete');

$aksesPerjalananModel = new AksesPerjalanan($pdo);

$aksesPerjalananModel->softDelete($_POST["id"], $_SESSION["user_id"]);

// header("Location: index.php");
header("Location: index.php?success=deleted");