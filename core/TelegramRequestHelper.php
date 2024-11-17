<?php
declare(strict_types=1);

namespace tg\core;

class TelegramRequestHelper
{
    public static function isCommand(string $text): bool
    {
        return str_starts_with($text, '/');
    }

    public static function getTelegramText(): string
    {
        if (self::isCallbackQuery()) {
            return App::get()->getTg()->callbackQuery->getData();
        }

        return App::get()->getTg()->message->getText();
    }

    public static function isCallbackQuery(): bool
    {
        return isset(App::get()->getTg()->callbackQuery);
    }
}