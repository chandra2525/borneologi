<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/InfrastrukturObservasi.php";

secureSessionStart();

$infrastrukturObservasiModel = new InfrastrukturObservasi($pdo);

$infrastrukturObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");