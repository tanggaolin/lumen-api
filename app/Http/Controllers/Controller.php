<?php
namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Exceptions\ErrorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {
        //记录进入ctrl层的耗时
        if (!isset($_ENV['_TS_']['_CTRL_START_'])) {
            $_ENV['_TS_']['_CTRL_START_'] = microtime(true);
        }
    }

    // 接口默认返回数据
    protected $result = ['code' => 0, 'msg' => 'ok', 'data' => [], "rid" => LOG_TRACE_ID];

    // 参数校验错误信息
    protected $validationMsg = [
        'required' => '参数 :attribute 不能为空',
        'email' => '参数 :attribute 是非法邮箱',
        'date' => '参数 :attribute 是非法日期',
        'integer' => '参数 :attribute 是非法整数',
        'boolean' => '参数 :attribute 是非法布尔值',
        'mimes' => '文件类型不合法',
        'max' => '文件过大'
    ];

    /**
     * 接口参数校验
     * @param Request $request
     * @param array $rules
     * @return bool
     * @throws CustomException
     */
    protected function checkParam(Request $request, array $rules)
    {
        $res = Validator::make($request->all(), $rules, $this->validationMsg);
        if (!$res->fails()) {
            return true;
        }
        $msg = $res->errors()->first();
        throw new CustomException(ErrorType::INVALID_PARAM, $msg);
    }
}
