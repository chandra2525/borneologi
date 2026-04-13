<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/KelompokTani.php";

secureSessionStart();

$kelompokTaniModel = new KelompokTani($pdo);

$kelompokTaniModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");