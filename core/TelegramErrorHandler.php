<?php
declare(strict_types=1);

namespace tg\core;

use Gotyefrid\MelkoframeworkCore\AbstractErrorHandler;
use Throwable;

class TelegramErrorHandler extends AbstractErrorHandler
{
    public function handle(Throwable $throwable): string
    {
        if ($this->isDebug()) {
            // Проверка, если это localhost или IP в диапазоне 192.168.*
            if (in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']) || preg_match('/^192\.168\./', $_SERVER['REMOTE_ADDR'])) {
                throw $throwable; // выбрасываем исключение в случае отладки на локалке
            }
        }

        $text = 'Вызвано исключение: ' . get_class($throwable);
        $text .= PHP_EOL . $throwable->getMessage();

        $tg = App::get()->getTg();
        $chatId = $tg->message->getChat()->getId();

        $tg->api->sendMessage([
            'chat_id' => $chatId,
            'text' => $text
        ]);

        return $text;
    }
}