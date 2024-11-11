<?php

namespace core;

class Application
{
    public static $appPath = __DIR__;

    /**
     * @var AppConfig
     */
    public static $app;

    /**
     * @var string
     */
    public static $dbPath = __DIR__ . '/../databases/database.db';

    public function __construct()
    {
        $request = new Request();
        self::$app = new AppConfig(
            new Router($request),
            new ErrorHandler(),
            $request,
            new \PDO('sqlite:' . self::$dbPath)
        );
    }

    /**
     * @return void
     */
    public function run(): void
    {
        try {
            echo self::$app->router->resolve();
        } catch (\Throwable $e) {
            echo Application::$app->errorHandler->handle($e);
        }
    }
}