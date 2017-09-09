<?php
if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
        $headers = array ();
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        // $msg = PHP_EOL . 'getallheaders():'
        //     . PHP_EOL . '  headers = ' . print_r($headers, true);
        // $msg .= PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return $headers;
    }
}
