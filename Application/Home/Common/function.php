<?php

/**
 * 加密给定密码。
 * @param string $password 给定密码。
 * @return string 经过加密的密码密文。
 */
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

/**
 * 判断当前用户是否已经登录。
 * @return bool 当前用户是否已经登录。
 */
function isAuthenticated()
{
    return session('?authentication.authenticated')
        && session('authentication.authenticated');
}

/**
 * 获取给当前登录的用户。
 * @return array 当前登录的用户，如未登录则返回 null。
 */
function getAuthenticatedUser()
{
    return isAuthenticated() ? session('authentication.user') : null;
}

/**
 * 获取给定用户的全名。
 * @param  mixed       $user     给定用户(数组或对象)或者用户的名。
 * @param  string|null $lastName 给定用户的姓。
 * @return string                给定用户的全名。
 */
function getUserFullName(mixed $user, string $lastName = null)
{
    $msg = 'getUserFullName():'
        . PHP_EOL . '  $user = ' . print_r($user, true)
        . PHP_EOL . '  $lastName = ' . $lastName;

    $fullName = "";

    if (is_string($user)) {
        $firstName = $user;
    } elseif (is_array($user)) {
        if (!isset($user['profile'])) {
            $msg .= PHP_EOL . '  read profile';
            $user['profile'] = D('Profile')
                ->where(['user_id' => $user['id']])->find();
        }
        $firstName = $user['profile']['first_name'];
        $lastName = $user['profile']['last_name'];
    } elseif (is_subclass_of($user, 'ProfileModel')) {
        if (!isset($user->profile)) {
            $msg .= PHP_EOL . '  read profile';
            $user->profile = D('Profile')
                ->where(['user_id' => $user['id']])->find();
        }
        $firstName = $user->profile->first_name;
        $lastName = $user->profile->last_name;
    }

    if (substr(LANG_SET, 0, 2) === 'zh') {
        $fullName = $lastName . ' ' . $firstName;
    } else {
        $fullName = $firstName . ' ' . $lastName;
    }

    $msg .= PHP_EOL . '  $fullName = ' . $fullName
        . PHP_EOL . str_repeat('-', 80);
    // \Think\Log::write($msg, 'DEBUG');

    return $fullName;
}
