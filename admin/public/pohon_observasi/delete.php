<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/PohonObservasi.php";

secureSessionStart();

$pohonObservasiModel = new PohonObservasi($pdo);

$pohonObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");