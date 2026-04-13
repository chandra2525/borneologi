<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/StatusKawasan.php";

secureSessionStart();

$statusKawasanModel = new StatusKawasan($pdo);

$statusKawasanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");