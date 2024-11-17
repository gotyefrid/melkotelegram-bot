<?php /** @noinspection PhpUnused */
/** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

use tg\core\App;
use tg\core\TelegramErrorHandler;
use tg\core\TelegramRequest;
use tg\core\TelegramRouter;
use tg\services\TelegramService;

if (preg_match('/\.(?!php|db$).+$/', $_SERVER["REQUEST_URI"])) {
    return false;  // server returns all files except those specified directly
}

$pdo = new PDO('sqlite:' . __DIR__ . '/databases/database.db');
$tgRouter = new TelegramRouter($pdo);
$request = new TelegramRequest($tgRouter);

$app = new App(
    new TelegramService(),
    $request,
    $pdo,
    new TelegramErrorHandler(true)
);
$app->run();

/**
 * Dumper
 * @param mixed ...$values
 *
 * @return void
 */
function dd(...$values): void
{
    var_dump(...$values);die;
}