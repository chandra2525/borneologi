<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/FiturTambahan.php";

secureSessionStart();

$fiturTambahanModel = new FiturTambahan($pdo);

$fiturTambahanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");