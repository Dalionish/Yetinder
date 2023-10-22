<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('email', 'password', 'role')
            ->from('user')
            ->where('email = ?')
            ->setParameter(0, $user->getUserIdentifier());
        try {
            $data = $queryBuilder->fetchAssociative();
        } catch (Exception $e) {
            throw new UserNotFoundException($e);
        }
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setRoles(array($data['role']));
        return $user;
    }


    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('email', 'password', 'role')
            ->from('user')
            ->where('email = ?')
            ->setParameter(0, $identifier);
        $data = null;
        try {
            $data = $queryBuilder->fetchAssociative();
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }
        if (!$data) {
            throw new UserNotFoundException('no user found');
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setRoles(array($data['role']));
        return $user;
    }

    public function supportsClass($class): bool
    {
        return $class === User::class;
    }
}