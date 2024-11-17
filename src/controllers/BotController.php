<?php /** @noinspection PhpUnused */

namespace src\controllers;

use core\Application;
use core\Controller;
use core\exceptions\BadRequestException;
use core\exceptions\NotFoundException;
use core\FlashMessageWidget;
use src\models\User;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Message;

class BotController extends Controller
{
    public static $title = 'Бот';

    public function __construct()
    {
        parent::__construct();
    }
    public function actionStart(): void
    {
        $tgBot = Application::$app->tgbot;
        $chatId = $tgBot->message->getChat()->getId();
        $text = $tgBot->message->getText();

        $tgBot->api->sendMessage([
            'chat_id' => $chatId,
            'text' => "Рады приветствовать вас. Изучите команды",
        ]);
    }

    /**
     * @return string
     * @throws NotFoundException
     * @throws \Throwable
     */
    public function actionStartProcess(): void
    {
        $tgBot = Application::$app->tgbot;
        $chatId = $tgBot->message->getChat()->getId();

        $tgBot->api->sendMessage([
            'chat_id' => $chatId,
            'text' => "Вы не выбрали команду",
        ]);
    }

    public function actionNewsleep(): void
    {
        $tgBot = Application::$app->tgbot;
        $chatId = $tgBot->message->getChat()->getId();
        $text = $tgBot->message->getText();

        $tgBot->api->sendMessage([
            'chat_id' => $chatId,
            'text' => "Запшишите что нибудь",
        ]);
    }
    public function actionNewsleepProcess(): void
    {
        $tgBot = Application::$app->tgbot;
        $chatId = $tgBot->message->getChat()->getId();
        $text = $tgBot->message->getText();

        $tgBot->api->sendMessage([
            'chat_id' => $chatId,
            'text' => "Вы написали " . $text,
        ]);
    }

    public function actionChooseCommand(): void
    {
        $tgBot = Application::$app->tgbot;
        $chatId = $tgBot->message->getChat()->getId();
        $text = $tgBot->message->getText();

        $tgBot->api->sendMessage([
            'chat_id' => $chatId,
            'text' => "Выберите команду (начните писать '/')",
        ]);
    }


    /**
     * @throws NotFoundException
     * @throws \Throwable
     */
    public function actionCreate()
    {
        $user = new User();

        if ($this->request->isPost()) {
            $user->username = $_POST['username'];
            $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if ($user->validate() && $user->save()) {
                FlashMessageWidget::setFlash('success', 'Успешно создано');
                return $this->redirect('user/index');
            }
        }

        return $this->render('create', ['model' => $user, 'errors' => $user->errors]);
    }

    /**
     * @return int|string
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws \Throwable
     */
    public function actionUpdate()
    {
        $id = $_GET['id'] ? (int)$_GET['id'] : null;

        if (!$id) {
            throw new BadRequestException('Не передан id');
        }

        /** @var User $user */
        $user = User::findById($id);

        if (!$user) {
            throw new NotFoundException('Не найден пользователь');
        }

        if ($this->request->isPost()) {
            $user->username = $_POST['username'];
            $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if ($user->validate() && $user->save()) {
                FlashMessageWidget::setFlash('success', 'Успешно обновлено');
                return $this->redirect('user/index');
            }
        }

        return $this->render('update', ['model' => $user, 'errors' => $user->errors]);
    }

    /**
     * @throws BadRequestException
     */
    public function actionDelete(): int
    {
        $id = $_GET['id'] ? (int)$_GET['id'] : null;

        if (!$id) {
            throw new BadRequestException('Не передан id');
        }

        $user = User::findById($id);

        if (!$user) {
            FlashMessageWidget::setFlash('danger', 'Не найден пользователь');
            return $this->redirect('user/index');
        }

        $user->delete();
        FlashMessageWidget::setFlash('success', 'Успешно удалено');

        return $this->redirect('user/index');
    }
}