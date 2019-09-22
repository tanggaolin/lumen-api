<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter as BaseJsonFormatter;

class JsonFormatter extends BaseJsonFormatter
{
    const MODE_RUNTIME = 'runtime';
    const MODE_API_LOG = 'api-log';

    public function format(array $record)
    {
        // 这个就是最终要记录的数组，最后转成Json并记录进日志
        $newRecord = [
            'pname' => env("APP_NAME"),
            "level" => $record["level_name"],
            'action' => CURRENT_API ? CURRENT_API : "unknown",
            'mode' => self::MODE_RUNTIME,
            'time' => $record['datetime']->format('Y-m-d H:i:s'),
            'trace_id' => LOG_TRACE_ID,
            'msg' => $record['message'],
        ];

        if (!empty($record['context'])) {
            if(isset($record['context']['mode']) && $record['message'] == self::MODE_API_LOG) {
                $newRecord = array_merge($newRecord, $record['context']);
            }else{
                $newRecord['msg'] .= json_encode($record['context']);
            }
        }
        $json = $this->toJson($this->normalize($newRecord), true) . ($this->appendNewline ? "\n" : '');
        return $json;
    }
}