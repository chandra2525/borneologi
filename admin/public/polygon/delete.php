<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Polygon.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Polygon', 'delete');

$polygonModel = new Polygon($pdo);

$polygonModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");