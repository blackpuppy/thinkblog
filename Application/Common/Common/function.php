<?php

use Carbon\Carbon;

/**
 * 检查给定字符串是否以给定的子字符串开始。
 * @return bool 给定字符串是否以给定的子字符串开始。
 */
function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return substr($haystack, 0, $length) === $needle;
}

/**
 * 检查给定字符串是否以给定的子字符串结尾。
 * @return bool 给定字符串是否以给定的子字符串结尾。
 */
function endsWith($haystack, $needle)
{
    $length = strlen($needle);

    return $length === 0 ||
        substr($haystack, -$length) === $needle;
}

/**
 * 获取给当前时间。
 * @return string 当前时间。
 */
function getNow()
{
    // return date('Y-m-d H:i:s.u');

    $now = Carbon::now();
    $nowStr = $now->toDateTimeString('Y-m-d H:i:s') . '.' . $now->micro;
    // $msg = PHP_EOL . 'getNow(): returns ' . $nowStr
    //     . PHP_EOL . str_repeat('-', 80);
    // \Think\Log::write($msg, 'DEBUG');

    return $nowStr;
}

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
function getCurrentUser()
{
    return isAuthenticated() ? session('authentication.user') : null;
}

/**
 * 获取给当前登录的用户的 id。
 * @return array 当前登录用户的 id，如未登录则返回 0。
 */
function getCurrentUserId()
{
    $isAuthenticated = isAuthenticated();
    $currentUserId = $isAuthenticated ? getCurrentUser()['id'] : 0;
    return $currentUserId;
}

/**
 * 获取给定用户的全名。
 * @param  string|array|object  $user     给定用户(数组或对象)或者用户的名。
 * @param  string|null          $lastName 给定用户的姓。
 * @return string                         给定用户的全名。
 */
function getUserFullName($user, string $lastName = null)
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

    if (empty(trim($fullName))) {
        if (is_array($user)) {
            $fullName = $user['name'];
        } elseif (is_subclass_of($user, 'ProfileModel')) {
            $fullName = $user->name;
        }
    }

    $msg .= PHP_EOL . '  $fullName = ' . $fullName
        . PHP_EOL . str_repeat('-', 80);
    // \Think\Log::write($msg, 'DEBUG');

    return $fullName;
}

/**
 * 递归去除给定数组中指定的键。
 * @param  array &$data        给定数组
 * @param  array $unwantedKeys 指定的键
 * @return void
 */
function recursiveUnset(&$data, $unwantedKeys) {
    foreach ($unwantedKeys as $key) {
        unset($data[$key]);
    }
    foreach ($data as &$value) {
        if (is_array($value)) {
            recursiveUnset($value, $unwantedKeys);
        }
    }

    // $msg = PHP_EOL . 'recursiveUnset(): '
    //     . PHP_EOL . '  $unwantedKeys = ' . print_r($unwantedKeys, true)
    //     . PHP_EOL . '  $data = ' . print_r($data, true)
    //     . PHP_EOL . str_repeat('-', 80);
    // \Think\Log::write($msg, 'DEBUG');
}
