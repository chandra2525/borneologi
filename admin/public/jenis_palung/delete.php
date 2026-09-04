<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/JenisPalung.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Jenis Palung', 'delete');

$jenisPalungModel = new JenisPalung($pdo);

$jenisPalungModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");