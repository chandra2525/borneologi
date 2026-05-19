<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/PerairanObservasi.php";

secureSessionStart();

$perairanObservasiModel = new PerairanObservasi($pdo);

$perairanObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");