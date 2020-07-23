<?php

namespace App\Traits;

namespace App\Http\Middleware;

use App\Logging\JsonFormatter;
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
        if (
            !empty($allowOriginArr)
            && (in_array($origin, $allowOriginArr)
                || env('ACCESS_ALLOW_ORIGIN') == '*')
        ) {
            if (env('ACCESS_ALLOW_ORIGIN') == '*') {
                $origin = '*';
            }
            $headers = [
                'Access-Control-Allow-Origin'  => $origin,
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
        //OPTIONS 请求不落日志
        if ($request->isMethod('OPTIONS')) {
            return;
        }
        $shutdown = microtime(true);
        $ts = ($shutdown - $_ENV["_TS_"]["_START_"]) * 1000;
        $logData = [
            "mode"   => JsonFormatter::MODE_API_LOG,
            "method" => $request->method(),
            "req"    => $request->all(),
            "rsp"    => json_decode($response->getContent(), true),
            "ts"     => intval($ts),
            "ip"     => $request->ip(),
        ];
        Log::info(JsonFormatter::MODE_API_LOG, $logData);
    }

}
