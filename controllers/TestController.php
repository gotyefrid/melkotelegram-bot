<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace tg\controllers;

use Gotyefrid\MelkoframeworkCore\exceptions\NotFoundException;
use Telegram\Bot\Exceptions\TelegramSDKException;
use tg\core\App;
use tg\services\TelegramService;
use Throwable;

class TestController extends BaseTelegramController
{
    /**
     * @return void
     * @throws TelegramSDKException
     */
    public function actionAction(): void
    {
        $this->tg->api->sendMessage([
            'chat_id' => $this->chatId,
            'text' => "Тест",
        ]);
    }
}