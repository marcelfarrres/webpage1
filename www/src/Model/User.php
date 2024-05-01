<?php

declare(strict_types=1);

namespace PWP\Model;

use DateTime;

final class User
{

    private string $email;
    private string $password; 
    private string $username;  
    private string $profile_picture;
    private DateTime $created_at;
    private DateTime $updated_at;

    public function __construct(
        string $email,
        string $password,
        DateTime $created_at,
        DateTime $updated_at
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->username = '';
        $this->profile_picture = '';
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }
    
    public function profile_picture(): string
    {
        return $this->profile_picture;
    }

    public function setProfile_picture($profile_picture): void
    {
        $this->profile_picture = $profile_picture;
    }

    public function created_at(): DateTime
    {
        return $this->created_at;
    }

    public function updated_at(): DateTime
    {
        return $this->updated_at;
    }
}