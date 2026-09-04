<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Kaleka.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Kaleka', 'delete');

$kalekaModel = new Kaleka($pdo);

$kalekaModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");