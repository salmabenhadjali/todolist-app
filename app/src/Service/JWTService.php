<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

class JWTService
{
    private $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function getExpirationDate(string $token): ?\DateTime
    {
        try {
            // Decode the JWT
            $payload = $this->jwtManager->parse($token);

            // Check if the "exp" claim exists
            if (!isset($payload['exp'])) {
                return null;
            }

            // Convert the Unix timestamp to a DateTime object
            $expirationDate = new \DateTime();
            $expirationDate->setTimestamp($payload['exp']);

            return $expirationDate;
        } catch (JWTDecodeFailureException $e) {
            // Handle invalid tokens
            return null;
        }
    }

    public function create(UserInterface $user)
    {
        return $this->jwtManager->create($user);
    }
}
