<?php

require_once __DIR__.'/../vendor/autoload.php';

//配置运行环境
$envFile = '.env';
if ($env = get_cfg_var('env')) {
    $envFile = sprintf("%s.%s", $envFile, $env);
}
(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__) . "/env",
    $envFile
))->bootstrap();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     App\Http\Middleware\ExampleMiddleware::class
// ]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

// 加载自定义配置文件
//$app->configure('app');

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);


//自定义常用的系统常量
define('APP_PATH', dirname(__DIR__));
$traceId = str_replace('-', '', Ramsey\Uuid\Uuid::uuid1()->toString());
define('LOG_TRACE_ID', $traceId);         // 日志追踪标记
/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

define('ROUTE_FILE', ['web']);
$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    foreach (ROUTE_FILE as $file) {
        require base_path("routes/{$file}.php");
    }
});

return $app;
