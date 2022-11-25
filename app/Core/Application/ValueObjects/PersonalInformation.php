<?php

namespace App\Core\Application\ValueObjects;

class PersonalInformation {
    public function __construct(
        private readonly string $email,
        private readonly string $firstName,
        private readonly string $lastName,
    ) {}

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string {
        return $this->lastName;
    }

    public function toArray(): array {
        return [
            'email' => $this->getEmail(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName()
        ];
    }
}