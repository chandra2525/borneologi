<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/TipePenanaman.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Tipe Penanaman', 'delete');

$tipePenanamanModel = new TipePenanaman($pdo);

$tipePenanamanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");