<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/MonitoringPenanaman.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Monitoring Penanaman', 'delete');

$monitoringPenanamanModel = new MonitoringPenanaman($pdo);

$monitoringPenanamanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");