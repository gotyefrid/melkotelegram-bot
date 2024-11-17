<?php /** @noinspection PhpUnused */

namespace src\controllers;

use core\Application;
use core\Controller;
use src\models\User;

class MigrationController extends Controller
{
    public function actionMigrate(): void
    {
        $this->initUserTable();
        $this->initDiaryTable();
        $this->initTelegramStatesTable();
    }

    public function actionCreateUser(): void
    {
        $this->createUser();
    }

    private function createUser(): void
    {
        $user = new User();
        $user->username = 'user' . rand(1, 999999);
        $user->password = password_hash('admin', PASSWORD_DEFAULT);
        $user->save();
        echo "Юзер создан: Логин $user->username пароль admin <br>";
    }

    private function initUserTable(): void
    {
        $result = Application::$app->db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users';");

        if ($result->fetch()) {
            echo "Таблица users уже существует <br>";
        } else {
            Application::$app->db->exec("CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY,
                    username TEXT,
                    password TEXT
                )");
            echo "Таблица users создана <br>";
        }
    }

    private function initDiaryTable(): void
    {
        $result = Application::$app->db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='sleep_records';");

        if ($result->fetch()) {
            echo "Таблица sleep_records уже существует <br>";
        } else {
            Application::$app->db->exec("CREATE TABLE sleep_records (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                sleep_date DATE NOT NULL,
                start_time TIME NOT NULL,
                end_time TIME NOT NULL,
                sleep_type TEXT CHECK( sleep_type IN ('night', 'day') ) NOT NULL
            );");
            echo "Таблица sleep_records создана <br>";
        }
    }

    private function initTelegramStatesTable()
    {
        $result = Application::$app->db->query(
            "SELECT name FROM sqlite_master WHERE type='table' AND name='tg_states';"
        );

        if ($result->fetch()) {
            echo "Таблица sleep_records уже существует <br>";
        } else {
            Application::$app->db->exec("CREATE TABLE tg_states (
                id INTEGER PRIMARY KEY,
                chat_id INTEGER,
                state TEXT,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );");
            echo "Таблица tg_states создана <br>";
        }
    }
}