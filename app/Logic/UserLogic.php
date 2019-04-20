<?php
/**
 * Description: 用户相关逻辑操作
 * Author: tanggaolin
 * Date: 2019-04-19
 */

namespace App\Logic;

use App\Exceptions\CustomException;
use App\Exceptions\ErrorType;
use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;

class UserLogic
{

    const TOKEN_LEN = 36;
    const SECONDS_IN_A_DAY = 86400;

    /**
     * 用户登录验证
     * @param string $username
     * @param string $password
     * @return array
     * @throws CustomException
     */
    public function login(string $mobile, string $password)
    {
        //普通管理员登录
        $userInfo = (new UserModel())->getOne(["mobile" => $mobile]);
        if (!$userInfo || in_array($userInfo['status'], ["OFF", "DELETE"])) { //邮箱不存在
            throw new CustomException(ErrorType::LOGIC_ERROR, '用户名密码不匹配');
        }
        $password = md5($password . $userInfo['salt']);
        if ($userInfo['passwd'] != $password) { //用户名密码不匹配
            throw new CustomException(ErrorType::LOGIC_ERROR, '用户名密码不匹配');
        }
        unset($userInfo['passwd'], $userInfo['salt']);

        return $this->doLogin($userInfo);
    }


    /**
     * 执行登录操作(设置 session)
     * @param array $admin
     * @return array
     * @throws CustomException
     */
    public function doLogin(array $userInfo)
    {
        $ret = [
            'token'       => str_random(self::TOKEN_LEN),
            'id'          => $userInfo['id'],
            'mobile'      => $userInfo['mobile'],
            'name'        => $userInfo['name'],
            'first_login' => $userInfo['extra'] && ($userInfo['extra'] == 'FIRST_LOGIN') //判断用户或管理员是否是第一次登录
        ];
        $cache = [
            'user_info' => $ret,
        ];
        // 设置缓存数据(session)
        Redis::setex($ret['token'], self::SECONDS_IN_A_DAY * 2, json_encode($cache));
        return $ret;
    }

}