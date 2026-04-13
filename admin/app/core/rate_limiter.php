<?php

function getClientIP()
{
    return $_SERVER['REMOTE_ADDR'];
}

function recordLoginAttempt($pdo, $username)
{
    $ip = getClientIP();

    $sql = "INSERT INTO login_attempts (username,ip_address)
            VALUES (:username,:ip)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "username" => $username,
        "ip" => $ip
    ]);
}

function tooManyAttempts($pdo, $username)
{

    $ip = getClientIP();

    $sql = "SELECT COUNT(*) as total
            FROM login_attempts
            WHERE (
                username = :username
                OR ip_address = :ip
            )
            AND attempt_time > (NOW() - INTERVAL 15 MINUTE)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "username" => $username,
        "ip" => $ip
    ]);

    $row = $stmt->fetch();

    return $row['total'] >= 5;
}

function clearAttempts($pdo, $username)
{

    $ip = getClientIP();

    $sql = "DELETE FROM login_attempts
            WHERE username=:username
            OR ip_address=:ip";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "username" => $username,
        "ip" => $ip
    ]);
}