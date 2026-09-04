<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/KondisiJalan.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Kondisi Jalan', 'delete');

$kondisiJalanModel = new KondisiJalan($pdo);

$kondisiJalanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");