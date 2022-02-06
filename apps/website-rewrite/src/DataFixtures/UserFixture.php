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

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setEmail("admin@localhost");
        $admin->setUsername("Administrator");
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);

        $user = new User();
        $user->setEmail("user@localhost");
        $user->setUsername("User");
        $user->setPassword($this->hasher->hashPassword($user, 'user'));
        $manager->persist($user);

        $manager->flush();
    }
}
