<?php
/**
 * Description: 系统公共函数
 * Author: tanggaolin
 * Date: 2019-02-01
 */


/**
 * 获取当前请求的接口名称
 * @return string
 */
function getURI()
{
    $path = explode('?', $_SERVER['REQUEST_URI'])[0];
    $path = '/' . strtolower(trim($path, '/ '));
    return $path;
}

