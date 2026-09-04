<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/ProgressStatusMonitoring.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Progress Monitoring', 'delete');

$progressStatusMonitoringModel = new ProgressStatusMonitoring($pdo);

$progressStatusMonitoringModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");