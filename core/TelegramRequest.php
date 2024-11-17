<?php
declare(strict_types=1);

namespace tg\core;

use Gotyefrid\MelkoframeworkCore\AbstractRequest;
use Gotyefrid\MelkoframeworkCore\Controller;
use Gotyefrid\MelkoframeworkCore\exceptions\NotFoundException;
use PDO;

class TelegramRequest extends AbstractRequest
{
    private TelegramRouter $router;

    public function __construct(
        TelegramRouter $router,
        $controllerNamespace = 'tg\\controllers\\',
        $routeParameterName = 'route',
        $defaultRoute = 'home/index'
    )
    {
        $this->router = $router;

        parent::__construct($controllerNamespace, $routeParameterName, $defaultRoute);
    }

    public function resolve()
    {
        $this->route = $this->parseRoute();
        $routeKeys = explode('/', $this->route);
        $this->controllerId = $routeKeys[0];
        $this->actionId = $this->snakeToCamel($routeKeys[1] ?? '');

        if (!$this->actionId) {
            $this->actionId = 'index';
        }

        $controllerInstance = $this->getControllerInstance($this->controllerId);

        if (!$controllerInstance) {
            throw new NotFoundException('Не найден такой контроллер');
        }

        return $controllerInstance->callAction($this->actionId);
    }

    private function snakeToCamel(string $string): string
    {
        if (!$string) {
            return '';
        }

        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }

    private function getControllerInstance(string $controllerName): ?Controller
    {
        $controllerClass = $this->controllerNamespace . ucfirst($controllerName) . 'Controller';

        if (!class_exists($controllerClass)) {
            return null;
        }

        return new $controllerClass();
    }

    public function getRouter(): TelegramRouter
    {
        return $this->router;
    }

    private function parseRoute()
    {
        if ($_SERVER['REQUEST_URI'] === '/setup') {
            return 'home/setup';
        }

        if (strpos($_SERVER['REQUEST_URI'], 'migration') !== false) {
            return ltrim($_SERVER['REQUEST_URI'], '/');
        }

        if ($_SERVER['REQUEST_URI'] !== '/') {
            http_response_code(400);
            throw new \DomainException('No found PATH');
        }

        return $this->router->getRoute();
    }
}