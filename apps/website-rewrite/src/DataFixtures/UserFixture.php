<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->makeAdmin('admin@localhost', 'Administrator', 'admin'));
        $manager->persist($this->makeUser('user@localhost', 'User', 'user'));
        $manager->persist($this->makeUser('user2@localhost', 'User2', 'user2'));

        $manager->flush();
    }

    public function makeAdmin(string $email, string $username, string $plainPassword): User
    {
        $admin = new User();
        $admin->setEmail($email);
        $admin->setUsername($username);
        $admin->setPassword($this->hasher->hashPassword($admin, $plainPassword));
        $admin->addRole('ROLE_ADMIN');

        return $admin;
    }

    public function makeUser(string $email, string $username, string $plainPassword): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword($this->hasher->hashPassword($user, $plainPassword));

        return $user;
    }
}
