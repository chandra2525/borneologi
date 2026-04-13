<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/Role.php";

secureSessionStart();

$roleModel = new Role($pdo);

$roleModel->softDelete($_POST["id"], $_SESSION["user_id"]);

// header("Location: index.php");
header("Location: index.php?success=deleted");