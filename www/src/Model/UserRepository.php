<?php

declare(strict_types=1);

namespace PWP\Model;

interface UserRepository
{
    public function save(User $user): void;

    public function checkIfUserExists(string $email): bool;

    public function checkIfUserExistsAndPasswordMatches(string $email, string $password): bool;

    public function getNum(string $email): int;
}