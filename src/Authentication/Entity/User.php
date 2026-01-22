<?php

namespace App\Authentication\Entity;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

class User implements JWTUserInterface
{
    public function __construct(
        private string $username,
        private array $roles,
    ) {
    }

    public static function createFromPayload($username, array $payload): self
    {
        return new self($username, $payload['roles']);
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function __toString(): string
    {
        return $this->username;
    }
}
