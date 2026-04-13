<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/KategoriArea.php";

secureSessionStart();

$kategoriAreaModel = new KategoriArea($pdo);

$kategoriAreaModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");