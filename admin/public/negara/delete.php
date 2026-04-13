<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/Negara.php";

secureSessionStart();

$negaraModel = new Negara($pdo);

$negaraModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");