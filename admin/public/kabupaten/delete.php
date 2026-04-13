<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/Kabupaten.php";

secureSessionStart();

$kabupatenModel = new Kabupaten($pdo);
$kabupatenModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");