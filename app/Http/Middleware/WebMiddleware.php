<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use App\Exceptions\ErrorType;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class WebMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     *
     * @return mixed
     * @throws CustomException
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $token = $request->input('token');
        if (empty($token)) {
            $info = $request->headers->all();
            $token = $info['authorization'][0] ?? '';
        }
        //验证token
        if (empty($token)) {
            throw new CustomException(ErrorType::ACCESS_DENY);
        }
        $data = json_decode(Redis::get($token), true);
        if (!$data || json_last_error() != 0) {
            throw new CustomException(ErrorType::ACCESS_DENY);
        }

        //更多校验逻辑

        //设置用户信息
        setv("user_info", $data["user_info"]);

        return $next($request);
    }
}
