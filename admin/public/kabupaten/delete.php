<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Kabupaten.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Kabupaten', 'delete');

$kabupatenModel = new Kabupaten($pdo);
$kabupatenModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");