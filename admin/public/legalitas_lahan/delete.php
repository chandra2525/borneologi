<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/LegalitasLahan.php";

secureSessionStart();

$legalitasLahanModel = new LegalitasLahan($pdo);

$legalitasLahanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");