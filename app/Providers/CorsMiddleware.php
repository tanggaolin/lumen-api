<?php
/**
 * Created by PhpStorm.
 * User: gaolintang
 * Date: 2019/8/17
 * Time: 6:36 PM
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $headers = [];
        $origin = $request->server('HTTP_HOST');
        $allowOriginArr = explode(',', env('ACCESS_ALLOW_ORIGIN', ''));
        //判断是否是允许跨域的地址或者全部允许跨域
        if (
            !empty($allowOriginArr) &&
            (in_array($origin, $allowOriginArr) || env('ACCESS_ALLOW_ORIGIN') == '*')
        ) {
            if (env('ACCESS_ALLOW_ORIGIN') == '*') {
                $origin = '*';
            }
            $headers = [
                'Access-Control-Allow-Origin' => $origin,
                'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, mid'
            ];

            if ($request->isMethod('OPTIONS')) {
                return response('', 200, $headers);
            }
        }

        $response = $next($request);
        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }


    public function terminate(Request $request, Response $response)
    {
        //记录请求参数和的返回日志
        $logData = [
            "ip" =>$request->ip(),
            "params" =>$request->all(),
            "response" =>$response->getContent()
        ];
        Log::info("[{$request->method()}] request api: {$request->path()}}", $logData);
    }

}
