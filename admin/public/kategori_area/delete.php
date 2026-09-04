<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/KategoriArea.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Kategori Area', 'delete');

$kategoriAreaModel = new KategoriArea($pdo);

$kategoriAreaModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");