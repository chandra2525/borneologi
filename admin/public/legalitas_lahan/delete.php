<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/LegalitasLahan.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Legalitas Lahan', 'delete');

$legalitasLahanModel = new LegalitasLahan($pdo);

$legalitasLahanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");