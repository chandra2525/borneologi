<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Provinsi.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Provinsi', 'delete');

$provinsiModel = new Provinsi($pdo);

$provinsiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");