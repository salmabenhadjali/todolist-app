<?php

declare(strict_types=1);

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginSuccessListener
{
    private JWTManager $jwtManager;

    public function __construct(JWTManager $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function onLoginSuccess(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        // Generate the JWT token
        $token = $this->jwtManager->create($user);

        // Store the token in the session or cookie if necessary
        $event->getRequest()->getSession()->set('api_token', $token);

        // Optionally redirect the user or send the token to the frontend
        // For example, you can return it as part of a response if you want the user
        // to be automatically authenticated for API requests.
    }
}
