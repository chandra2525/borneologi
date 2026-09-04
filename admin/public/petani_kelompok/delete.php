<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/PetaniKelompok.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Kelompok Penerima', 'delete');

$petaniKelompokModel = new PetaniKelompok($pdo);

$petaniKelompokModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");