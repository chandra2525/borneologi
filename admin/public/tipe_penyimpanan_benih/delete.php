<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/TipePenyimpananBenih.php";

secureSessionStart();

$tipePenyimpananBenihModel = new TipePenyimpananBenih($pdo);

$tipePenyimpananBenihModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");