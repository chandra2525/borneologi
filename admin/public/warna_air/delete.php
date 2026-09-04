<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/WarnaAir.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Warna Air', 'delete');

$warnaAirModel = new WarnaAir($pdo);

$warnaAirModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");