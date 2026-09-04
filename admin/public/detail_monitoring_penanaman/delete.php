<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/DetailMonitoringPenanaman.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Detail Monitoring', 'delete');

$detailMonitoringPenanamanModel = new DetailMonitoringPenanaman($pdo);

$detailMonitoringPenanamanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");