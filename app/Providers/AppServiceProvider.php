<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        //框架初始化完成的时间
        $_ENV['_TS_']['_BOOT_'] = microtime(true);
        // 注册结束回调函数
        register_shutdown_function([$this, 'shutdown']);

        // 设置错误级别
        if (app()->environment('prod')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
        }

        //记录SQL-log
        if (env('DB_LOG', false)) {
            \DB::listen(function ($query) {
                $sql = str_replace("?", "'%s'", $query->sql);
                $sql = vsprintf($sql, $query->bindings) . " | {$query->time}";
                Log::channel('sql-daily')->info($sql);
                if ($query->time > 100) {
                    Log::warning('SLOOOOOW-SQL: ' . $sql);
                }
            });
        }
    }

    public function shutdown()
    {
        $shutdown    = microtime(true);
        echo  $shutdown;
        echo PHP_EOL;
        $logInfo     = str_pad(CURRENT_API, 28);
        $_BOOT       = ($_ENV["_TS_"]["_BOOT_"] - $_ENV["_TS_"]["_START_"]) * 1000;
        $_CTRL_START = ($_ENV["_TS_"]["_CTRL_START_"] - $_ENV["_TS_"]["_START_"]) * 1000;
        $_SHUTDOWN   = ($shutdown - $_ENV["_TS_"]["_START_"]) * 1000;
        $logInfo     .= sprintf(" BOOT:%d CTRL_START:%d SHUTDOWN:%d", intval($_BOOT), intval($_CTRL_START),
            intval($_SHUTDOWN));
        Log::channel('ts-daily')->info($logInfo);

        //elk日志相关记录可以继续在此处写

    }
}
