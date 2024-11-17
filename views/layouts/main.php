<?php
declare(strict_types=1);

/**
 * @var string $content
 * @var string $title
 */

use Gotyefrid\MelkoframeworkCore\FlashMessageWidget;
?>

<!DOCTYPE html>
<html lang="ru">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/views/layouts/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="/views/layouts/css/bootstrap-icons.css">
    <title><?= $title ?? 'Title' ?></title>
    <script src="/views/layouts/js/jquery-3.7.1.js"></script>
    <script src="https://unpkg.com/htmx.org@2.0.3"></script>
</head>
<body>
<?php require('main-nav.php') ?>
<div class="container mt-3">
    <div class="flash-messages">
        <?= FlashMessageWidget::showFlashIfExist() ?>
    </div>
    <div class="page-content">
        <?= $content ?>
    </div>
</div>
<script src="/views/layouts/js/bootstrap.bundle.js"></script>
</body>
</html>
