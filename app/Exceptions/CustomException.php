<?php
/**
 * Description: 自定义异常类
 * Author: yaoqianpeng<yaoqianpeng@cmcm.com>
 * Date: 2018-03-29
 */

namespace App\Exceptions;

class CustomException extends \Exception
{
    public function __construct(int $code, string $message = '')
    {
        $message = $message ?: ErrorType::msg($code);
        parent::__construct($message, $code);
    }
}

class ErrorType
{

    // 错误状态码
    const SUCCESS = 0;      //成功状态码
    const INVALID_PARAM = -1; //参数错误
    const ACCESS_DENY = 403; //无权访问
    const PROCESS_ERROR = 500;  //系统错误
    const LOGIC_ERROR = 501;   //通用逻辑错误

    private static $errorMsg = [
        self::SUCCESS => 'OK',
        self::INVALID_PARAM => '参数错误',
        self::ACCESS_DENY => '无权访问',
        self::PROCESS_ERROR => '系统错误',
        self::LOGIC_ERROR => '通用逻辑错误',
    ];

    public static function msg(int $code)
    {
        return self::$errorMsg[$code] ?? '未知错误';
    }
}
