<?php

use App\Application;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: dirname(__DIR__) . '/routes/web.php',
    )->create();

$app->useEnvironmentPath(dirname(__DIR__, 2))->setNamespace('App');

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

return $app;
