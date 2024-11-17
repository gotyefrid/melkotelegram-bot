<?php
declare(strict_types=1);

namespace tg\services;

use Telegram\Bot\Api;

class TelegramService
{
    public $message = null;

    public $callbackQuery = null;
    public const TOKEN = '7550356332:AAGA_HB1Mvt5hJbxB4iS5d-jam-OJwdX0-I';
    public ?Api $api = null;

    public function __construct()
    {
        $telegram = new Api(self::TOKEN);
        $this->api = $telegram;
        $update = $telegram->getWebhookUpdate();
        $this->message = $update->getMessage();
        $this->callbackQuery = $update->getCallbackQuery();
    }
}