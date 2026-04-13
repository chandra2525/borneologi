<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/PenggunaanPertanian.php";

secureSessionStart();

$penggunaanPertanianModel = new PenggunaanPertanian($pdo);

$penggunaanPertanianModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");