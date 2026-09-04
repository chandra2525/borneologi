<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Petani.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Penerima Manfaat', 'delete');

$petaniModel = new Petani($pdo);

$petaniModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");