<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Negara.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Negara', 'delete');

$negaraModel = new Negara($pdo);

$negaraModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");