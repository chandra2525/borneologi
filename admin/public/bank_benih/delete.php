<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/BankBenih.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Bank Benih', 'delete');

secureSessionStart();

$bankBenihModel = new BankBenih($pdo);

$bankBenihModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");