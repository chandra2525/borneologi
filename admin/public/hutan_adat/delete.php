<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/HutanAdat.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Hutan Adat', 'delete');

$hutanAdatModel = new HutanAdat($pdo);

$hutanAdatModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");