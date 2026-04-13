<?php

function passwordHash($password)
{
return password_hash($password, PASSWORD_ARGON2ID);
}

function passwordVerify($password,$hash)
{
return password_verify($password,$hash);
}

function passwordRehashNeeded($hash)
{
return password_needs_rehash($hash,PASSWORD_ARGON2ID);
}