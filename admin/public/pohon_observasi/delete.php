<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/PohonObservasi.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Pohon Observasi', 'delete');

$pohonObservasiModel = new PohonObservasi($pdo);

$pohonObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");