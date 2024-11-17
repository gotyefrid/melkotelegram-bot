<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace tg\controllers;

use Gotyefrid\MelkoframeworkCore\Controller;
use tg\services\AuthService;

class BaseController extends Controller
{
    public function getViewsDir(): string
    {
        return __DIR__ . '/../views' ;
    }

    public function getLayoutsDir(): string
    {
        return __DIR__ . '/../views/layouts' ;
    }


    public function checkAuth(): void
    {
        $auth = new AuthService();

        if (!$auth->isAuthenticated()) {
            $this->redirect('auth/login');
        }
    }
}