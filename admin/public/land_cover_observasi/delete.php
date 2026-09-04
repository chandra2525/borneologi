<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/LandCoverObservasi.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Land Cover Observasi', 'delete');

$landCoverObservasiModel = new LandCoverObservasi($pdo);

$landCoverObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");