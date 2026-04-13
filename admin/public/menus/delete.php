<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/Menu.php";

secureSessionStart();

$menuModel = new Menu($pdo);

$menuModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");