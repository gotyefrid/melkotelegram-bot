<?php
/** @var array $errors */

/** @var User $model */

use core\helpers\Renderer;
use core\helpers\Url;
use src\models\User;

?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h3 class="text-center mb-4">Редактировать пользователя</h3>
            <?= Renderer::render(__DIR__ . '/_form.php', [
                'errors' => $errors,
                'model' => $model,
                'update' => true,
                'route' => Url::toRoute('user/update', ['id' => $model->id]),
            ]) ?>
        </div>
    </div>
</div>
