<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Menu.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Menus', 'delete');

$menuModel = new Menu($pdo);

$menuModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");