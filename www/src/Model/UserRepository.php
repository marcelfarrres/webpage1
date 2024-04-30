<?php

declare(strict_types=1);


namespace PWP\Model;
use PWP\Model\User;

interface UserRepository
{
    public function save(User $user): void;

    public function checkIfUserExists(string $email): bool;

    public function checkIfUserExistsAndPasswordMatches(string $email, string $password): bool;

    public function getUserbyEmail(string $email): User;

    public function updateUserUsername(string $email, string $newUsername): bool;
    public function updateProfileImage(string $email, string $newProfilePicture): bool;
}