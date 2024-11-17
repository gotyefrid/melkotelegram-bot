<?php
declare(strict_types=1);

namespace tg\core;

use Gotyefrid\MelkoframeworkCore\AbstractErrorHandler;
use Gotyefrid\MelkoframeworkCore\AbstractRequest;
use Gotyefrid\MelkoframeworkCore\App as BaseApp;
use PDO;
use tg\services\TelegramService;

class App extends BaseApp
{
    private TelegramService $tg;

    public function __construct(TelegramService $tg, AbstractRequest $request, PDO $pdo, AbstractErrorHandler $errorHandler)
    {
        $this->tg = $tg;

        parent::__construct($request, $pdo, $errorHandler);
    }

    public function getTg(): TelegramService
    {
        return $this->tg;
    }
}