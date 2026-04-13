<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/Petani.php";

secureSessionStart();

$petaniModel = new Petani($pdo);

$petaniModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");