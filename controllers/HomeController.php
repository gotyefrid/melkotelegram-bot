<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace tg\controllers;

use Gotyefrid\MelkoframeworkCore\exceptions\NotFoundException;
use tg\core\App;
use Throwable;

class HomeController extends BaseController
{
    public static string $title = 'Главная';

    /**
     * @return string
     * @throws Throwable
     * @throws NotFoundException
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    public function actionSetup()
    {
        $telegram = App::get()->getTg()->api;
        $telegram->setWebhook([
            'url' => 'https://9d48442ecef932a576bf2e02b88d45bb.serveo.net'
        ]);
        // Устанавливаем команды для бота
        $telegram->setMyCommands([
            'commands' => [
                ['command' => 'start', 'description' => 'Начало работы с ботом'],
                ['command' => 'set_name', 'description' => 'Команда 1'],
                ['command' => 'test__action', 'description' => 'Команда 2']
            ],
        ]);
    }
}