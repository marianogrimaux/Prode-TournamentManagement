<?php

declare(strict_types=1);
namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

final class User implements JWTUserInterface {



    public function __construct(
        private string $username,
        private array $roles,
        private int $id,
        private string $email
    )
    {
    }

    public static function createFromPayload($username, array $payload) : User
    {
        return new self(
            $username,
            $payload['roles'],
            $payload['id'],
            $payload['email']
        );
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}