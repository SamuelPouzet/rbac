<?php

namespace SamuelPouzet\Rbac\Listener;

use Application\Controller\LoginController;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SamuelPouzet\Auth\Enumerations\AuthStatusEnum;
use SamuelPouzet\Rbac\Service\AuthService;

class AuthListener
{
    protected array $listeners = [];

    public function attach(EventManagerInterface $events, int $priority = 1): void
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this, 'authorize'],
            $priority
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function authorize(MvcEvent $event): void
    {
        try {
            $authService = $event->getApplication()->getServiceManager()->get(AuthService::class);
            $result = $authService->authenticate($event);

            if ($result->getStatus() !== AuthStatusEnum::GRANTED) {
                $this->redirectToLogin($event);
            }
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
    }

    protected function redirectToLogin(MvcEvent $event): void
    {
        $event->getRouteMatch()->setParam('controller', LoginController::class);
        $event->getRouteMatch()->setParam('action', 'index');
    }
}
