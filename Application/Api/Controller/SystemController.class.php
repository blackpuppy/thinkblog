<?php
namespace Api\Controller;

use Api\Controller\BaseController;

class SystemController extends BaseController
{
    /**
     * 读取翻译。
     * @return void
     */
    public function translate()
    {
        // $msg = PHP_EOL . 'Api\Controller\SystemController::translate():'
        //     . PHP_EOL . '  REQUEST_METHOD = ' . REQUEST_METHOD;

        if (!IS_GET) {
            $msg .= PHP_EOL . '  not GET, 405 method not allowed'
                . PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');

            $this->response(L('METHOD_NOT_ALLOWED'), 'json', 405);
            return;
        }

        $data = L();
        $data['THINK_VERSION'] = THINK_VERSION;
        $data['FOOTER_MESSAGE'] = L('FOOTER_MESSAGE', [
            'appName' => L('APPLICATION_NAME'),
            'thinkphpVersion' => THINK_VERSION,
        ]);
        $data['REMOVE_ENTRY_IN_URL'] = L('REMOVE_ENTRY_IN_URL', [
            'entry_file' => 'index.php'
        ]);

        array_walk($data, function(&$value)
        {
            $value = str_replace('{$', '{{', $value);
            $value = str_replace('}', '}}', $value);
        });

        // $msg .= PHP_EOL . '  $data = ' . print_r($data, true);
        // $msg .= PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        $this->response($data, 'json', 200);
    }
}
