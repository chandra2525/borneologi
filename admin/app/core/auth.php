<?php

require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/security.php";

function login($pdo, $username, $password)
{

    $userModel = new User($pdo);
    $user = $userModel->findByUsername($username);

    if (!$user)
        return false;

    // if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
    //     die("Account locked");
    // }

    if (!passwordVerify($password, $user['password_hash'])) {

        $userModel->increaseLoginFail($user['id']);

        if ($user['failed_login_count'] >= 5) {
            $userModel->lockUser($user['id']);
        }

        return false;
    }

    if (passwordRehashNeeded($user['password_hash'])) {

        $newHash = passwordHash($password);

        $sql = "UPDATE t_users SET password_hash=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$newHash, $user['id']]);

    }

    $userModel->updateLoginSuccess($user['id']);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['id_role'];

    session_regenerate_id(true);

    return true;

}

function checkAuth($typeMenu = null)
{

    if (!isset($_SESSION['user_id']) && $typeMenu == "dashboard") {
        header("Location: login.php");
        exit;
    } else if (!isset($_SESSION['user_id']) && $typeMenu == "non_dashboard") {
        header("Location: ../login.php");
        exit;
    }

}