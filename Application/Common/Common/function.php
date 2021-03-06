<?php

use Api\Controller\BaseController;
use Carbon\Carbon;

if (!function_exists('startsWith')) {
    /**
     * 检查给定字符串是否以给定的子字符串开始。
     * @return bool 给定字符串是否以给定的子字符串开始。
     */
    function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return substr($haystack, 0, $length) === $needle;
    }
}

if (!function_exists('endsWith')) {
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
}

if (!function_exists('str_limit')) {
    /**
     * Limit the number of characters in a string.
     *
     * @param  string  $value
     * @param  int     $limit
     * @param  string  $end
     * @return string
     */
    function str_limit($value, $limit = 100, $end = '...')
    {
        if (mb_strlen($value) <= $limit) {
            return $value;
        }

        return rtrim(mb_substr($value, 0, $limit, 'UTF-8')) . $end;
    }
}

if (!function_exists('getNow')) {
    /**
     * 获取给当前时间。
     * @return string 当前时间。
     */
    function getNow()
    {
        // return date('Y-m-d H:i:s.u');

        $now    = Carbon::now();
        $nowStr = $now->toDateTimeString('Y-m-d H:i:s') . '.' . $now->micro;
        // $msg = PHP_EOL . 'getNow(): returns ' . $nowStr
        //     . PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return $nowStr;
    }
}

if (!function_exists('encryptPassword')) {
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
}

if (!function_exists('isAuthenticated')) {
    /**
     * 判断当前用户是否已经登录。
     * @return bool 当前用户是否已经登录。
     */
    function isAuthenticated()
    {
        $authenticated = false;

        if (MODULE_NAME === 'Api') {
            $authenticated = BaseController::isAuthenticated();
        } else {
            $authenticated = session('?authentication.authenticated')
            && session('authentication.authenticated');
        }

        return $authenticated;
    }
}

if (!function_exists('getCurrentUser')) {
    /**
     * 获取给当前登录的用户。
     * @return array 当前登录的用户，如未登录则返回 null。
     */
    function getCurrentUser()
    {
        $currentUser = null;

        if (MODULE_NAME === 'Api') {
            $currentUser = BaseController::getCurrentUser();
        } else {
            $currentUser = isAuthenticated() ? session('authentication.user') : null;
        }

        return $currentUser;
    }
}

if (!function_exists('getCurrentUserId')) {
    /**
     * 获取给当前登录的用户的 id。
     * @return int 当前登录用户的 id，如未登录则返回 0。
     */
    function getCurrentUserId()
    {
        $currentUserId = null;

        if (MODULE_NAME === 'Api') {
            $currentUserId = BaseController::getCurrentUserId();
        } else {
            $isAuthenticated = isAuthenticated();
            $currentUserId   = $isAuthenticated ? getCurrentUser()['id'] : 0;
        }

        return (int) $currentUserId;
    }
}

if (!function_exists('getUserFullName')) {
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
            $lastName  = $user['profile']['last_name'];
        } elseif (is_subclass_of($user, 'ProfileModel')) {
            if (!isset($user->profile)) {
                $msg .= PHP_EOL . '  read profile';
                $user->profile = D('Profile')
                ->where(['user_id' => $user['id']])->find();
            }
            $firstName = $user->profile->first_name;
            $lastName  = $user->profile->last_name;
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
}

if (!function_exists('recursiveUnset')) {
    /**
     * 递归去除给定数组中指定的键。
     * @param  array &$data        给定数组
     * @param  array $unwantedKeys 指定的键
     * @return void
     */
    function recursiveUnset(&$data, $unwantedKeys)
    {
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
}

if (!function_exists('sendMail')) {
    /**
     * 发送邮件。
     * @param array  $to             给定数组
     * @param string $subject        邮件标题
     * @param string $body           邮件内容
     * @return bool 当前用户是否已经登录。
     */
    function sendMail($to, $subject, $body)
    {
        $encryptionType = C('SMTP_ENCRYPTION_TYPE');
        $host           = C('SMTP_HOST');
        $port           = C('SMTP_PORT');
        $username       = C('SMTP_USERNAME');
        $password       = C('SMTP_PASSWORD');
        $fromAddr       = C('FROM_ADDRESS');
        $fromName       = C('FROM_NAME');

        $transport = (new Swift_SmtpTransport($host, $port, $encryptionType))
        ->setUsername($username)
        ->setPassword($password);
        $mailer  = new Swift_Mailer($transport);
        $message = (new Swift_Message($subject))
        ->setFrom([$fromAddr => $fromName])
        ->setTo($to)
        ->setBody($body, 'text/html');

        $result = $mailer->send($message);

        $msg = 'sendMail():'
        . PHP_EOL . '  $to = ' . print_r($to, true)
        . PHP_EOL . '  $subject = ' . $subject
        . PHP_EOL . '  $body = ' . $body
        . PHP_EOL . '  $result = ' . $result;
        $msg .= PHP_EOL . str_repeat('-', 80);
        \Think\Log::write($msg, 'DEBUG');

        return $result;
    }
}

if (!function_exists('getConstant')) {
    /**
     * 获取给定名字常量的值
     *
     * @param  string $name 常量名字
     *
     * @return mixed  给定名字常量的值
     *
     * @author 朱明 <mingzhu.z+gitlab@gmail.com>
     */
    function getConstant($name)
    {
        if (defined($name)) {
            return constant($name);
        }
        return '[not defined]';
    }
}

if (!function_exists('defineConstant')) {
    /**
     * 获取给定名字常量的值
     *
     * @param string $name  常量名字
     * @param mixed  $value 值
     *
     * @return bool 成功时返回 TRUE， 或者在失败时返回 FALSE。
     *
     * @author 朱明 <mingzhu.z+gitlab@gmail.com>
     */
    function defineConstant($name, $value)
    {
        if (defined($name)) {
            return function_exists('runkit_constant_redefine')
                && runkit_constant_redefine($name, $value);
        }
        return define($name, $value);
    }
}

if (!function_exists('getMethod')) {
    function getMethod($class, $name)
    {
        $class  = new ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
