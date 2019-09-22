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
function getURI(): string
{
    if (!isset($_SERVER['REQUEST_URI'])) {
        global $argv;
        $cliName = $argv[1] ?? "";
        return $cliName;
    }
    $path = explode('?', $_SERVER['REQUEST_URI'])[0];
    $path = '/' . strtolower(trim($path, '/ '));
    return $path;
}

/**
 * 设置全局环境变量
 * @param string $key
 * @param        $value
 */
function setv(string $key, $value)
{
    $_ENV[SYS_VARS][$key] = $value;
}

/**
 * 获取该项目的全局变量
 * @param string $key
 * @return null
 */
function getv(string $key)
{
    return $_ENV[SYS_VARS][$key] ?? null;
}

// @TODO
function arrayFilter(array $attr, array $keys): array
{
    return array_filter($attr, function ($key) use ($keys) {
        return in_array($key, $keys);
    }, ARRAY_FILTER_USE_KEY);
}

/**
 * 拆分字符串并过滤
 * @param string $delimiter
 * @param string $content
 * @return array
 */
function explodeX(string $delimiter, string $content)
{
    return array_unique(array_filter(explode($delimiter, $content)));
}