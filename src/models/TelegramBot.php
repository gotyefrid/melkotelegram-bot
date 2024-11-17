<?php
declare(strict_types=1);

namespace src\models;

use Telegram\Bot\Api;

class TelegramBot
{
    public $message = null;

    public const string TOKEN = '7550356332:AAGA_HB1Mvt5hJbxB4iS5d-jam-OJwdX0-I';
    public ?Api $api = null;
    public function __construct()
    {
        $telegram = new Api(self::TOKEN);
        $telegram->setWebhook(['url' => 'https://ee94f0efd93c87.lhr.life']);
        $telegram->commandsHandler(true);
        // Устанавливаем команды для бота
        $telegram->setMyCommands([
            'commands' => [
                ['command' => 'start', 'description' => 'Начало работы с ботом'],
                ['command' => 'newsleep', 'description' => 'Записать новый сон ребёнка']
            ],
        ]);

        $update = $telegram->getWebhookUpdate();
        $this->message = $update->getMessage();

        $this->api = $telegram;
    }
}