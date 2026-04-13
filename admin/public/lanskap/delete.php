<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/Lanskap.php";

secureSessionStart();

$lanskapModel = new Lanskap($pdo);

$lanskapModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");