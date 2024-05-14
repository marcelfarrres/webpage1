<?php

declare(strict_types=1);

namespace PWP\Model\Repository; 

use PDO;
use PWP\Model\User;
use PWP\Model\UserRepository;
use DateTime;

final class MysqlUserRepository implements UserRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDO $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function save(User $user): void
    {
        $query = <<<'QUERY'
        INSERT INTO users(email, password, created_at, updated_at)
        VALUES(:email, :password, :created_at, :updated_at)
        QUERY;
        
        $statement = $this->database->prepare($query);

        $email = $user->email();
        $password = $user->password();
        $created_at = $user->created_at()->format(self::DATE_FORMAT);
        $updated_at = $user->updated_at()->format(self::DATE_FORMAT);

        $statement->bindParam('email', $email, PDO::PARAM_STR);
        $statement->bindParam('password', $password, PDO::PARAM_STR);
        $statement->bindParam('created_at', $created_at, PDO::PARAM_STR);
        $statement->bindParam('updated_at', $updated_at, PDO::PARAM_STR);

        $statement->execute();
    }

    public function checkIfUserExists(string $email): bool 
    {
        $query = <<<'QUERY'
        SELECT COUNT(*) as count FROM users WHERE email = :email
        QUERY;
    
        $statement = $this->database->prepare($query);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    }

    public function checkIfUserExistsAndPasswordMatches(string $email, string $password): bool
{
    $query = <<<'QUERY'
    SELECT password FROM users WHERE email = :email
    QUERY;
    $hashedPassword = "";
    $statement = $this->database->prepare($query);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();
    if($statement->rowCount() == 0){

    }else{
        $hashedPassword = password_hash($statement->fetchColumn(), null);
    }

    

    if ($hashedPassword !== false) {
        if (password_verify($password, $hashedPassword)) {
            return true;
        } else {
            echo(" Incorrect password: " . $password . " / " . $hashedPassword);
            return false;
        }
    } else {
        // User does not exist
        echo(" User does not exist");
        return false;
    }
}

public function getUserbyEmail(string $email): User
{
    $query = <<<'QUERY'
    SELECT * FROM users WHERE email = :email
    QUERY;

    $statement = $this->database->prepare($query);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();

    $data = $statement->fetch(PDO::FETCH_ASSOC);

    // Create new user
    $user = new User(
        $data['email'],
        '',
        new DateTime($data['created_at']),
        new DateTime($data['updated_at'])
    );
    $user->setUsername($data['username']);
    $user->setProfile_picture($data['profile_picture']);
    $user->setId($data['id']);
    return $user;
}

public function updateUserUsername(string $email, string $newUsername): bool
{
    $query = <<<'QUERY'
    UPDATE users SET username = :username WHERE email = :email
    QUERY;

    $statement = $this->database->prepare($query);
    $statement->bindParam(':username', $newUsername, PDO::PARAM_STR);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);

    return $statement->execute();
}

public function updateProfileImage(string $email, string $newProfilePicture): bool
{
    $query = <<<'QUERY'
    UPDATE users SET profile_picture = :profile_picture WHERE email = :email
    QUERY;

    $statement = $this->database->prepare($query);
    $statement->bindParam(':profile_picture', $newProfilePicture, PDO::PARAM_STR);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);

    return $statement->execute();
}


public function isUsernameUnique(string $username): bool
{
    $query = <<<'QUERY'
    SELECT COUNT(*) AS count FROM users WHERE username = :username
    QUERY;

    $statement = $this->database->prepare($query);
    $statement->bindParam(':username', $username, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);
    
    return $result['count'] === 0;
}




}