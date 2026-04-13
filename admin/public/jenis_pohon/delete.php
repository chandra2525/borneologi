<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/JenisPohon.php";

secureSessionStart();

$jenisPohonModel = new JenisPohon($pdo);

$jenisPohonModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");