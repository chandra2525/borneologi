<?php

require "../../app/core/session.php";
require "../../app/config/database.php";
require "../../app/models/PetaniKelompok.php";

secureSessionStart();

$petaniKelompokModel = new PetaniKelompok($pdo);

$petaniKelompokModel->softDelete($_POST["id"], $_SESSION["user_id"]);

header("Location: index.php?success=deleted");