<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/PerairanObservasi.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Perairan Observasi', 'delete');

$perairanObservasiModel = new PerairanObservasi($pdo);

$perairanObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");