<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/Provinsi.php";

secureSessionStart();

$provinsiModel = new Provinsi($pdo);

$provinsiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");