<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class TaskFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->makeTask('Titre tâche 1', 'Contenu tâche 1', '2022-02-16 00:00:00'));
        $manager->persist($this->makeTask('Titre tâche 2', 'Contenu Admin', '2022-02-17 00:00:00', UserFixture::ADMIN_USER));
        $manager->persist($this->makeTask('Titre tâche 3', 'Contenu tâche 2', '2022-02-17 00:00:00', UserFixture::ACTING_USER));
        $manager->persist($this->makeTask('Titre tâche 4', 'Contenu tâche 3', '2022-02-17 00:00:00', UserFixture::NON_ACTING_USER));

        $manager->flush();
    }

    /**
     * @throws Exception
     */
    private function makeTask(string $title, string $content, string $dateTime, ?int $userId = null): Task
    {
        $task = new Task();

        $task->setTitle($title);
        $task->setContent($content);
        $task->setCreatedAt(new DateTimeImmutable($dateTime));
        if ($userId) {
            $user = $this->userRepository->find($userId);

            if ($user instanceof User) {
                $task->setUser($user);
            }
        }

        return $task;
    }

    public function getDependencies(): array
    {
        return [UserFixture::class];
    }
}
