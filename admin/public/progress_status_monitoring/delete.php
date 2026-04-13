<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/ProgressStatusMonitoring.php";

secureSessionStart();

$progressStatusMonitoringModel = new ProgressStatusMonitoring($pdo);

$progressStatusMonitoringModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");