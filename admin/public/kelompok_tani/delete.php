<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/KelompokTani.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Kelompok', 'delete');

$kelompokTaniModel = new KelompokTani($pdo);

$kelompokTaniModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");