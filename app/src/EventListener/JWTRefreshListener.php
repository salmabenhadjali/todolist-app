<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Service\JWTService;
use DateTime;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class JWTRefreshListener implements EventSubscriberInterface
{
    private JWTService $jwtService;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $jwtToken = $request->getSession()->get('api_token');

        if ($jwtToken) {
            $currentDate = new DateTime();
            $expirationDate = $this->jwtService->getExpirationDate($jwtToken);
            if ($expirationDate >= $currentDate) {
                return;
            }
        }

        // Check if the user is authenticated in the web session
        $serializedSessionToken = $request->getSession()->get('_security_main') ?? null;  // Replace 'main' with your firewall name

        if (!$serializedSessionToken) {
            return;
        }

        $sessionToken = unserialize($serializedSessionToken);
        $user = $sessionToken->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        // Generate the new JWT token
        $token = $this->jwtService->create($user);

        // Store the token in the session or cookie if necessary
        $event->getRequest()->getSession()->set('api_token', $token);
    }
}
