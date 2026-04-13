<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/WarnaAir.php";

secureSessionStart();

$warnaAirModel = new WarnaAir($pdo);

$warnaAirModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");