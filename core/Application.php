<?php

namespace core;

use src\models\TelegramBot;

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
        if (!is_dir(dirname(self::$dbPath))) {
            mkdir(dirname(self::$dbPath), 0777, true);
        }

        $request = new Request();
        self::$app = new AppConfig(
            new Router($request),
            new ErrorHandler(),
            $request,
            new \PDO('sqlite:' . self::$dbPath),
            new TelegramBot(),
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