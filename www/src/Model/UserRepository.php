<?php

declare(strict_types=1);

namespace PWP\Model;

interface UserRepository
{
    public function save(User $user): void;
}