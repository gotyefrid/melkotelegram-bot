<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace tg\controllers;

use PDO;
use tg\core\App;
use tg\core\TelegramRequestHelper;
use tg\services\TelegramService;

class BaseTelegramController extends BaseController
{
    public TelegramService $tg;
    public int $chatId;
    public string $text;
    public PDO $db;

    public function __construct()
    {
        $this->tg = App::get()->getTg();
        $this->db = App::get()->getPdo();
        $this->chatId = $this->tg->message->getChat()->getId();
        $this->text = TelegramRequestHelper::getTelegramText();
        parent::__construct();
    }

    public function isCommand(): bool
    {
        return str_starts_with($this->text, '/');
    }
}