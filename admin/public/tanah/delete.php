<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/Tanah.php";

secureSessionStart();

$tanahModel = new Tanah($pdo);

$tanahModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");