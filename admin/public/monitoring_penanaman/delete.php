<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/MonitoringPenanaman.php";

secureSessionStart();

$monitoringPenanamanModel = new MonitoringPenanaman($pdo);

$monitoringPenanamanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");