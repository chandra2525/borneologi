<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/Kecamatan.php";

secureSessionStart();

$kecamatanModel = new Kecamatan($pdo);
$kecamatanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");