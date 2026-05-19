<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/DetailMonitoringPenanaman.php";

secureSessionStart();

$detailMonitoringPenanamanModel = new DetailMonitoringPenanaman($pdo);

$detailMonitoringPenanamanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");