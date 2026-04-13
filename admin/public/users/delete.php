<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/User.php";

secureSessionStart();

$userModel = new User($pdo);

$userModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location:index.php?success=deleted");