<?php

function csrfToken()
{
    if (empty($_SESSION['csrf_token']))
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrfField()
{
    $token = csrfToken();

    return '<input type="hidden" name="csrf_token" value="'.$token.'">';
}

function verifyCsrfToken()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    {
        return;
    }

    if (!isset($_POST['csrf_token']))
    {
        die("CSRF token missing");
    }

    if (!isset($_SESSION['csrf_token'])) {
        die("CSRF session token missing");
    }

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']))
    {
        die("Invalid CSRF token");
    }

    unset($_SESSION['csrf_token']);
}