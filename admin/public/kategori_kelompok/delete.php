<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/KategoriKelompok.php";

secureSessionStart();

$kategoriKelompokModel = new KategoriKelompok($pdo);

$kategoriKelompokModel->softDelete($_POST["id"], $_SESSION["user_id"]);

// header("Location: index.php");
header("Location: index.php?success=deleted");