<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/KecepatanAliran.php";

secureSessionStart();

$kecepatanAliranModel = new KecepatanAliran($pdo);

$kecepatanAliranModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");