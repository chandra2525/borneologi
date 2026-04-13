<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/KondisiJalan.php";

secureSessionStart();

$kondisiJalanModel = new KondisiJalan($pdo);

$kondisiJalanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");