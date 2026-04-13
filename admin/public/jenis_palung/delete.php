<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/JenisPalung.php";

secureSessionStart();

$jenisPalungModel = new JenisPalung($pdo);

$jenisPalungModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");