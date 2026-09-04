<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/TopografiObservasi.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Topografi Observasi', 'delete');

$topografiObservasiModel = new TopografiObservasi($pdo);

$topografiObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");