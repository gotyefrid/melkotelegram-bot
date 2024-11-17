<?php /** @noinspection PhpUnused */

namespace tg\controllers;

use PDO;
use tg\core\App;
use tg\models\User;

class MigrationController extends BaseController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = App::get()->getPdo();
        parent::__construct();
    }

    public function actionMigrate(): void
    {
        $this->initUserTable();
        $this->initTelegramStatesTable();
    }

    private function initUserTable(): void
    {
        $result = $this->db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users';");

        if ($result->fetch()) {
            echo "Таблица users уже существует <br>";
        } else {
            $this->db->exec("CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY,
                    username TEXT,
                    chat_id INTEGER
                )");
            echo "Таблица users создана <br>";
        }
    }

    private function initTelegramStatesTable()
    {
        $result = $this->db->query(
            "SELECT name FROM sqlite_master WHERE type='table' AND name='tg_states';"
        );

        if ($result->fetch()) {
            echo "Таблица sleep_records уже существует <br>";
        } else {
            $this->db->exec("CREATE TABLE tg_states (
                id INTEGER PRIMARY KEY,
                chat_id INTEGER,
                state TEXT,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );");
            echo "Таблица tg_states создана <br>";
        }
    }

    function getViewsDir(): string
    {
        return '';
    }

    function getLayoutsDir(): string
    {
        return '';
    }
}