<?php
declare(strict_types=1);

namespace tg\core;

use PDO;
use tg\services\TelegramService;

class TelegramRouter
{
    public string $defaultController = 'bot';
    private PDO $db;
    private TelegramService $tg;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getRoute(): string
    {
        $this->tg = App::get()->getTg();
        $chatId = $this->tg->message->getChat()->getId();
        $text = TelegramRequestHelper::getTelegramText();
        $db = $this->db;

        if (TelegramRequestHelper::isCommand($text)) {
            return $this->processCommandRoute($text);
        }

        $stmt = $db->prepare(
            "SELECT * FROM tg_states WHERE chat_id = :chat_id ORDER BY updated_at DESC LIMIT 1"
        );
        $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return "bot/error";
        }

        return $row['state'];

        //
        // if (str_ends_with($row['state'], 'Process')) {
        //     $this->route = 'bot/chooseCommand';
        //     return;
        // }
        //
        // $newState = $row['state'] . 'Process';
        // $this->route = 'bot/' . $newState;
        //
        // $insertStmt = $db->prepare(
        //     "INSERT INTO tg_states (chat_id, state) VALUES (:chat_id, :state)"
        // );
        // $insertStmt->bindParam(':chat_id', $chatId, \PDO::PARAM_INT);
        // $insertStmt->bindParam(':state', $newState);
        // $insertStmt->execute();
    }

    public function processCommandRoute(string $text): string
    {
        $text = str_replace('/', '', $text);

        if (str_contains($text, '__')){
            return str_replace('__', '/', $text);
        }

        return "$this->defaultController/$text";
        // Подготовленный запрос для вставки новых данных
        // $insertStmt = $db->prepare(
        //     "INSERT INTO tg_states (chat_id, state) VALUES (:chat_id, :state)"
        // );
        // $insertStmt->bindParam(':chat_id', $chatId, \PDO::PARAM_INT);
        // $insertStmt->bindParam(':state', $state);
        // $insertStmt->execute();
        // return;
    }
}