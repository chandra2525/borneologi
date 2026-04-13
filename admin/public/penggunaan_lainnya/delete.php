<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/PenggunaanLainnya.php";

secureSessionStart();

$penggunaanLainnyaModel = new PenggunaanLainnya($pdo);

$penggunaanLainnyaModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");