<?php

function encryptPassword($password)
{
    $hasedPassword = $password;

    if (password_needs_rehash($password, PASSWORD_BCRYPT)) {
        $hasedPassword = password_hash($password, PASSWORD_BCRYPT);
    }

    $msg = "encryptPassword(): $password --> $hasedPassword"
        . PHP_EOL . str_repeat('-', 80);
    // \Think\Log::write($msg, 'DEBUG');

    return $hasedPassword;
}

function isAuthenticated()
{
    return session('?authentication.authenticated')
        && session('authentication.authenticated');
}

function getAuthenticatedUser()
{
    return isAuthenticated() ? session('authentication.user') : null;
}
