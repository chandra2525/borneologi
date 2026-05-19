<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/TopografiObservasi.php";

secureSessionStart();

$topografiObservasiModel = new TopografiObservasi($pdo);

$topografiObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");