<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/StatusKawasan.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Status Kawasan', 'delete');

$statusKawasanModel = new StatusKawasan($pdo);

$statusKawasanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");