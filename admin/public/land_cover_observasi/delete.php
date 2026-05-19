<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/LandCoverObservasi.php";

secureSessionStart();

$landCoverObservasiModel = new LandCoverObservasi($pdo);

$landCoverObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");