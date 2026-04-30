<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/HutanAdat.php";

secureSessionStart();

$hutanAdatModel = new HutanAdat($pdo);

$hutanAdatModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");