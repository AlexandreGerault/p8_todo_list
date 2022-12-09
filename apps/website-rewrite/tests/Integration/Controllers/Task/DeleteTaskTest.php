<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers\Task;

class DeleteTaskTest extends TaskTest
{
    public function testAGuestCannotDeleteATask(): void
    {
        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => self::ACTING_USER_TASK_ID]);

        $this->client->request('GET', $taskDeleteUrl);
        $this->client->followRedirect();

        self::assertRouteSame('login');
    }

    public function testAUserCanDeleteATask(): void
    {
        $this->actingAsUser();

        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => self::ACTING_USER_TASK_ID]);

        $this->client->request('GET', $taskDeleteUrl);
        $crawler = $this->client->followRedirect();

        self::assertRouteSame('task_list');
        self::assertStringNotContainsString('Titre tâche ' . self::ACTING_USER_TASK_ID, $crawler->html());
    }

    public function testAnAdminCanDeleteATask(): void
    {
        $this->actingAsAdmin();

        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => self::ACTING_USER_TASK_ID]);

        $this->client->request('GET', $taskDeleteUrl);
        $crawler = $this->client->followRedirect();

        self::assertRouteSame('task_list');
        self::assertStringNotContainsString('Titre tâche ' . self::ACTING_USER_TASK_ID, $crawler->html());
    }
}
