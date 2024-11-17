<?php

namespace core;

use src\models\TelegramBot;
use Telegram\Bot\Api;

class AppConfig
{
    /**
     * @var Router
     */
    public $router;

    /**
     * @var ErrorHandler
     */
    public $errorHandler;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var \PDO
     */
    public $db;
    public TelegramBot $tgbot;

    public function __construct(
        Router $router,
        ErrorHandler $errorHandler,
        Request $request,
        \PDO $db,
        TelegramBot $tgbot
    )
    {
        $this->router = $router;
        $this->errorHandler = $errorHandler;
        $this->request = $request;
        $this->db = $db;
        $this->tgbot = $tgbot;
    }
}