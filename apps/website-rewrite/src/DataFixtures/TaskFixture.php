<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TaskFixture extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->makeTask('Titre tâche 1', 'Contenu tâche 1', '2022-02-16 00:00:00'));
        $manager->persist($this->makeTask('Titre tâche 2', 'Contenu tâche 2', '2022-02-17 00:00:00'));

        $manager->flush();
    }

    /**
     * @throws Exception
     */
    private function makeTask(string $title, string $content, string $dateTime): Task
    {
        $task = new Task();
        $task->setTitle($title);
        $task->setContent($content);
        $task->setCreatedAt(new DateTimeImmutable($dateTime));
        return $task;
    }
}
