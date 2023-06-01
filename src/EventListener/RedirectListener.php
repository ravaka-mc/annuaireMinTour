<?php

namespace App\EventListener;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RedirectListener implements EventSubscriberInterface
{

    private $router;
    public function __construct(RouterInterface $router) 
    {
        $this->router = $router;
    }

    /**
     * @param LoginSuccessEvent $event
     * @return void
     */
    public function onSuccessfulLogin(LoginSuccessEvent $event): void
    {
        $request = $event->getRequest();
        $token = $event->getAuthenticatedToken();

        if (!$request->hasSession() || !$request->hasPreviousSession()) {
            return;
        }

        $user = $token->getUser();
        if(in_array('ROLE_ETABLISSEMENT', $user->getRoles())){
            $event->setResponse(new RedirectResponse($this->router->generate('app_dashboard')));
        } else {
            $event->setResponse(new RedirectResponse($this->router->generate('app_admin')));
        }
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onSuccessfulLogin',
        ];
    }

}