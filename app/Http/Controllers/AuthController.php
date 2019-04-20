<?php
/**
 * Description: 用户认证相关
 * Author: tanggaolin
 * Date: 2019-04-19
 */

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Logic\UserLogic;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * 用户登录
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     * @throws CustomException
     */
    public function login(Request $r)
    {
        $this->checkParam($r, [
            'username' => 'required|string',
            'password'  => 'required|string'
        ]);
        $username             = $r->input('username');
        $password             = trim($r->input('password'));
        $this->result['data'] = (new UserLogic())->login($username, $password);
        return response()->json($this->result);
    }

    public function loginInfo() {
        $this->result["data"] = getv("user_info");
        return response()->json($this->result);
    }

}
