<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException or $exception instanceof CustomException) {
            return;
        }
        $severity = '';
        if ($exception instanceof \ErrorException) {
            $severity = $this->errorSeverityMap($exception->getSeverity());
            $severity = sprintf("#%s#: ", $severity);
        }
        $str = sprintf("%s\nFile: %s:%d\nTRACE: %s", $exception->getMessage(), $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString());
        Log::channel('error-daily')->error($severity . $str);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        // 404 not found
        if ($exception instanceof NotFoundHttpException) {
            return response('404. Not Found', 404);
        }
        // 403 forbidden
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response('403. Forbidden', 403);
        }

        // 本地环境直接输出错误
        if (app()->environment('local')) {
            return parent::render($request, $exception);
        }

        //测试环境输出错误日志
        if (app()->environment('qa')) {
            $code = $exception->getCode() ?? -1;
            $data = ['code' => $code, 'msg' => $exception->getMessage(), 'trace' => $this->getTopTrace($e)];
            return response()->json($data);
        }
        // 自定义错误信息展示出来
        if ($exception instanceof CustomException) {
            $data = ['code' => $exception->getCode(), 'msg' => $exception->getMessage()];
            return response()->json($data);
        }
        // 未知的错误信息
        $data = ['code' => -1, 'msg' => 'sorry, error occurred.'];
        return response()->json($data);
    }

    /**
     * 获取top错误跟踪信息
     * @param Exception $e
     * @return array
     */
    private function getTopTrace(\Exception $e)
    {
        $str = $e->getTraceAsString();
        $arr = explodeX(PHP_EOL . '#', $str);
        $num = min(10, count($arr));

        return array_slice($arr, 0, $num);
    }

    /**
     * php內建错误级别
     * @param int $n
     * @return array|mixed|string
     */
    private function errorSeverityMap(int $n)
    {
        $map = [
            E_ERROR             => 'E_ERROR',
            E_WARNING           => 'E_WARNING',
            E_PARSE             => 'E_PARSE',
            E_NOTICE            => 'E_NOTICE',
            E_CORE_ERROR        => 'E_CORE_ERROR',
            E_CORE_WARNING      => 'E_CORE_WARNING',
            E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
            E_USER_ERROR        => 'E_USER_ERROR',
            E_USER_WARNING      => 'E_USER_WARNING',
            E_USER_NOTICE       => 'E_USER_NOTICE',
            E_STRICT            => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED        => 'E_DEPRECATED',
            E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
        ];

        return $n ? ($map[$n] ?? '') : $map;
    }
}
