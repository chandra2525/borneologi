<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Lanskap.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Lanskap', 'delete');

$lanskapModel = new Lanskap($pdo);

$lanskapModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");