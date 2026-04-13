<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/Desa.php";

secureSessionStart();

$desaModel = new Desa($pdo);
$desaModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");