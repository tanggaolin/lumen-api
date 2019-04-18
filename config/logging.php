<?php

$logPath = rtrim(env('LOG_PATH', '/tmp'), '/') . DIRECTORY_SEPARATOR . env("APP_NAME");
$runtime = $logPath . '/runtime/runtime.log';
$error = $logPath . '/error/error.log';
$db = $logPath . '/sql/sql.log';
$ts = $logPath . '/ts//ts.log';
$job = $logPath . '/job/job.log';
var_dump($logPath);
return [
    'default' => env('LOG_CHANNEL', 'stack'),
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'name' => LOG_TRACE_ID,
            'channels' => ['runtime-daily'],
        ],
        'runtime-daily' => [
            'driver' => 'daily',
            'path' => $runtime,
            'level' => 'debug',
            'name' => LOG_TRACE_ID,
            'days' => 30,
        ],
        'error-daily' => [
            'driver' => 'daily',
            'path' => $error,
            'level' => 'debug',
            'name' => LOG_TRACE_ID,
            'days' => 30,
        ],
        'sql-daily' => [
            'driver' => 'daily',
            'path' => $db,
            'level' => 'debug',
            'name' => LOG_TRACE_ID,
            'days' => 30,
        ],
        'ts-daily' => [
            'driver' => 'daily',
            'path' => $ts,
            'level' => 'debug',
            'name' => LOG_TRACE_ID,
            'days' => 30,
        ],
        'job-daily' => [
            'driver' => 'daily',
            'path' => $job,
            'level' => 'debug',
            'name' => LOG_TRACE_ID,
            'days' => 30,
        ]
    ]
];