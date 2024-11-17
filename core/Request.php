<?php

namespace core;

use Telegram\Bot\Api;

class Request
{
    private $route;
    public $routeParameterName = 'route';

    public function __construct()
    {
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getAction()
    {
        return explode('/', $this->getRoute())[1] ?? '';
    }

    public function getController()
    {
        return explode('/', $this->getRoute())[0] ?? '';
    }

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function setRoute(string $path)
    {
        $this->route = $path;
    }

    public function setUpRoute(): void
    {
        $route = $_GET['route'] ?? null;

        if (!$route) {
            $path = $_SERVER['REQUEST_URI'];

            if ($path === '/') {
                $route = Router::DEFAULT_ROUTE;
            } else {
                $route = substr($path, 1);
            }
        }

        if ($route) {
            $this->route = $route;
            return;
        }

        $tgBot = Application::$app->tgbot;
        $chatId = $tgBot->message->getChat()->getId();
        $text = $tgBot->message->getText();

        $db = Application::$app->db;

        if (str_starts_with($text,  '/')) {
            $state = str_replace('/', '', $text);
            $this->route = "bot/$state";
            // Подготовленный запрос для вставки новых данных
            $insertStmt = $db->prepare(
                "INSERT INTO tg_states (chat_id, state) VALUES (:chat_id, :state)"
            );
            $insertStmt->bindParam(':chat_id', $chatId, \PDO::PARAM_INT);
            $insertStmt->bindParam(':state', $state);
            $insertStmt->execute();
            return;
        }

        // Подготовленный запрос для получения последней записи
        $stmt = $db->prepare(
            "SELECT * FROM tg_states WHERE chat_id = :chat_id ORDER BY updated_at DESC LIMIT 1"
        );
        $stmt->bindParam(':chat_id', $chatId, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            $this->route = "bot/error";
            return;
        }

        if (str_ends_with($row['state'], 'Process')) {
            $this->route = 'bot/chooseCommand';
            return;
        }

        $newState = $row['state'] . 'Process';
        $this->route = 'bot/' . $newState;

        $insertStmt = $db->prepare(
            "INSERT INTO tg_states (chat_id, state) VALUES (:chat_id, :state)"
        );
        $insertStmt->bindParam(':chat_id', $chatId, \PDO::PARAM_INT);
        $insertStmt->bindParam(':state', $newState);
        $insertStmt->execute();
    }
}