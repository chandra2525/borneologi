<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/InfrastrukturObservasi.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Infrastruktur Observasi', 'delete');

$infrastrukturObservasiModel = new InfrastrukturObservasi($pdo);

$infrastrukturObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");