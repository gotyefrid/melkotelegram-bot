<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace tg\controllers;

use Telegram\Bot\Exceptions\TelegramSDKException;
use tg\core\TelegramRequestHelper;
use tg\models\TgState;
use tg\models\User;

class BotController extends BaseTelegramController
{
    /**
     * @return void
     * @throws TelegramSDKException
     */
    public function actionStart(): void
    {
        $model = new TgState();
        $model->chat_id = $this->chatId;

        $markup = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => 'Установить имя', 'callback_data' => '/set_name'],
                    ['text' => 'Посмотреть имя', 'callback_data' => '/get_name'],
                ],
            ],
        ]);

        $model->state = 'bot/start';
        $model->save();

        if (TelegramRequestHelper::isCallbackQuery()) {
            $this->tg->api->editMessageText([
                'chat_id' => $this->chatId,
                'message_id' => $this->tg->message->getMessageId(),
                'text' => "Выберите команду:",
                'reply_markup' => $markup
            ]);

            $this->tg->api->answerCallbackQuery([
                'callback_query_id' => $this->tg->callbackQuery->getId(),
            ]);

            return;
        }

        $this->tg->api->sendMessage([
            'chat_id' => $this->chatId,
            'text' => "Выберите команду:",
            'reply_markup' => $markup,
        ]);
    }

    /**
     * @return void
     * @throws TelegramSDKException
     */
    public function actionSetName(): void
    {
        $tgState = new TgState();
        $tgState->chat_id = $this->chatId;
        $tgState->state = 'bot/setName';
        $tgState->save();

        if (TelegramRequestHelper::isCallbackQuery()) {
            $this->tg->api->editMessageText([
                'chat_id' => $this->chatId,
                'message_id' => $this->tg->message->getMessageId(),
                'text' => 'Введите ваше имя',
            ]);

            $this->tg->api->answerCallbackQuery([
                'callback_query_id' => $this->tg->callbackQuery->getId(),
            ]);

            return;
        }

        if (TelegramRequestHelper::isCommand($this->text)) {
            $this->tg->api->sendMessage([
                'chat_id' => $this->chatId,
                'text' => "Введите ваше имя",
            ]);

            return;
        }

        $user = User::findOne('SELECT * FROM ' . User::tableName() . ' WHERE chat_id = ?', [$this->chatId]);

        if (!$user) {
            $user = new User();
            $user->chat_id = $this->chatId;
        }

        $tgState->state = 'bot/start';
        $tgState->save();

        $user->username = $this->text;
        $user->save();

        $this->tg->api->sendMessage([
            'chat_id' => $this->chatId,
            'text' => "Имя установлено: " . $this->text,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => 'К командам', 'callback_data' => '/start'],
                    ],
                ],
            ]),
        ]);
    }

    /**
     * @return void
     * @throws TelegramSDKException
     */
    public function actionGetName(): void
    {
        $tgState = new TgState();
        $tgState->chat_id = $this->chatId;
        $tgState->state = 'bot/getName';
        $tgState->save();

        /** @var User $user */
        $user = User::findOne('SELECT * FROM ' . User::tableName() . ' WHERE chat_id = ?', [$this->chatId]);
        $answer = $user ? 'Ваше имя: ' . $user->username : "Вы еще не указали имя";
        $markup = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => 'Изменить имя', 'callback_data' => '/set_name'],
                    ['text' => 'К командам', 'callback_data' => '/start'],
                ],
            ],
        ]);

        if (TelegramRequestHelper::isCallbackQuery()) {
            $this->tg->api->editMessageText([
                'chat_id' => $this->chatId,
                'message_id' => $this->tg->message->getMessageId(),
                'text' => $answer,
                'reply_markup' => $markup,
            ]);

            $this->tg->api->answerCallbackQuery([
                'callback_query_id' => $this->tg->callbackQuery->getId(),
            ]);

            return;
        }

        $this->tg->api->sendMessage([
            'chat_id' => $this->chatId,
            'text' => $answer,
            'reply_markup' => $markup,
        ]);
    }
}