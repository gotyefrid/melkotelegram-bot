<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace tg\controllers;

use Telegram\Bot\Exceptions\TelegramSDKException;
use tg\core\TelegramRequestHelper;
use tg\models\TgState;

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

        if (TelegramRequestHelper::isCallbackQuery()) {
            $this->tg->api->editMessageReplyMarkup([
                'chat_id' => $this->chatId,
                'message_id' => $this->tg->message->getMessageId(),
                'reply_markup' => json_encode([
                    'inline_keyboard' => [],
                ]),
            ]);

            $this->tg->api->answerCallbackQuery([
                'callback_query_id' => $this->tg->callbackQuery->getId(),
            ]);

            $model->state = 'bot/start';
            $model->save();

            return;
        }

        $model->state = 'bot/start';
        $model->save();

        $this->tg->api->sendMessage([
            'chat_id' => $this->chatId,
            'text' => "Рады приветствовать вас. Изучите команды",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => 'Выбрать имя', 'callback_data' => '/set_name'],
                    ],
                ],
            ]),
        ]);
    }

    public function actionSetName()
    {
        $model = new TgState();
        $model->chat_id = $this->chatId;

        if (TelegramRequestHelper::isCallbackQuery()) {
            $this->tg->api->editMessageReplyMarkup([
                'chat_id' => $this->chatId,
                'message_id' => $this->tg->message->getMessageId(),
                'reply_markup' => json_encode([
                    'inline_keyboard' => [],
                ]),
            ]);

            $this->tg->api->answerCallbackQuery([
                'callback_query_id' => $this->tg->callbackQuery->getId(),
            ]);
        }

        if (TelegramRequestHelper::isCommand($this->text)) {
            $model->state = 'bot/setName';
            $model->save();

            $this->tg->api->sendMessage([
                'chat_id' => $this->chatId,
                'text' => "Напишите ваше имя",
            ]);
        } else {
            $model->state = 'bot/start';
            $model->save();

            $this->tg->api->sendMessage([
                'chat_id' => $this->chatId,
                'text' => "Имя установлено: " . $this->text,
            ]);
        }
    }
}