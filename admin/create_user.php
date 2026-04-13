<?php

require "app/config/database.php";

$password = password_hash("admin123", PASSWORD_ARGON2ID);

$sql = "INSERT INTO t_users 
(username,email,password_hash,nama_lengkap,id_role)
VALUES ('admin','admin@mail.com',?, 'Administrator',1)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$password]);

echo "User created";