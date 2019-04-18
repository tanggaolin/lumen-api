<?php

namespace App\Http\Controllers;

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
}
